<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Gig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk filter dropdown
        $categories = Category::all(); 

        // 2. Query untuk mengambil Gigs
        $query = Gig::with(['seller.user', 'category']);

        // Logika Pencarian (Search)
        $searchTerm = $request->input('query') ?? $request->input('search');
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category); // Sesuaikan dengan kolom slug/id
            });
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('min_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('min_price', '<=', $request->max_price);
        }

        // Filter by delivery time
        if ($request->filled('delivery_days')) {
            $query->where('delivery_days', '<=', $request->delivery_days);
        }

        // Sort options
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('min_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('min_price', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $gigs = $query->paginate(12)->withQueryString();

        // 3. KIRIM $categories KE VIEW (Ini yang sebelumnya kurang)
        return view('gigs.index', compact('gigs', 'categories'));
    }

    /**
     * Search suggestions for autocomplete
     */
    public function searchSuggestions(Request $request)
    {
        $search = $request->get('q', '');

        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $gigs = Gig::with(['seller.user', 'category'])
            ->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->limit(5)
            ->get()
            ->map(function ($gig) {
                return [
                    'id' => $gig->id,
                    'title' => $gig->title,
                    'category' => $gig->category->name,
                    'price' => $gig->max_price
                        ? '$' . number_format($gig->min_price, 0) . '+'
                        : '$' . number_format($gig->min_price, 0),
                    'seller' => $gig->seller->business_name,
                    'image' => $gig->images && count($gig->images) > 0
                        ? asset('storage/' . $gig->images[0])
                        : null,
                    'url' => route('gigs.show', $gig)
                ];
            });

        return response()->json($gigs);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is a seller
        if (!Auth::user()->is_seller) {
            return redirect()->route('seller.register')
                ->with('error', 'You must be a seller to create gigs.');
        }

        $categories = Category::orderBy('name')->get();
        $deliveryPresets = [1, 3, 7, 14, 30];

        return view('gigs.create', compact('categories', 'deliveryPresets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->is_seller) {
            return redirect()->route('seller.register')
                ->with('error', 'You must be a seller to create gigs.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'category_id' => 'required|exists:categories,id',
            'min_price' => 'required|numeric|min:5',
            'max_price' => 'nullable|numeric|min:5|gte:min_price',
            'delivery_days' => 'required|integer|min:1|max:365',
            'allows_tutoring' => 'boolean',
            'images.*' => 'nullable|image|max:2048',
            'attachments.*' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('gigs/images', 'public');
                $images[] = $path;
            }
        }

        // Handle attachment uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $path = $attachment->store('gigs/attachments', 'public');
                $attachments[] = [
                    'path' => $path,
                    'original_name' => $attachment->getClientOriginalName(),
                ];
            }
        }

        Gig::create([
            'seller_id' => Auth::user()->seller->id,
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'min_price' => $validated['min_price'],
            'max_price' => $validated['max_price'] ?? null,
            'delivery_days' => $validated['delivery_days'],
            'allows_tutoring' => $request->has('allows_tutoring'),
            'images' => !empty($images) ? $images : null,
            'attachments' => !empty($attachments) ? $attachments : null,
        ]);

        return redirect()->route('seller.dashboard')
            ->with('success', 'Gig created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gig $gig)
    {
        $gig->load(['seller.user', 'category']);
        return view('gigs.show', compact('gig'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gig $gig)
    {
        // Check if user owns the gig
        if ($gig->seller_id !== Auth::user()->seller->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check for active orders (to be implemented when orders exist)
        // For now, allow editing

        $categories = Category::orderBy('name')->get();
        $deliveryPresets = [1, 3, 7, 14, 30];

        return view('gigs.edit', compact('gig', 'categories', 'deliveryPresets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gig $gig)
    {
        // Check if user owns the gig
        if ($gig->seller_id !== Auth::user()->seller->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check for active orders (to be implemented when orders exist)
        // For now, allow updating

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'category_id' => 'required|exists:categories,id',
            'min_price' => 'required|numeric|min:5',
            'max_price' => 'nullable|numeric|min:5|gte:min_price',
            'delivery_days' => 'required|integer|min:1|max:365',
            'allows_tutoring' => 'boolean',
            'images.*' => 'nullable|image|max:2048',
            'attachments.*' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
            'remove_images' => 'nullable|array',
            'remove_attachments' => 'nullable|array',
        ]);

        // Handle image removal
        $currentImages = $gig->images ?? [];
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageToRemove) {
                if (($key = array_search($imageToRemove, $currentImages)) !== false) {
                    Storage::disk('public')->delete($imageToRemove);
                    unset($currentImages[$key]);
                }
            }
            $currentImages = array_values($currentImages);
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('gigs/images', 'public');
                $currentImages[] = $path;
            }
        }

        // Handle attachment removal
        $currentAttachments = $gig->attachments ?? [];
        if ($request->has('remove_attachments')) {
            foreach ($request->remove_attachments as $attachmentToRemove) {
                foreach ($currentAttachments as $key => $attachment) {
                    if ($attachment['path'] === $attachmentToRemove) {
                        Storage::disk('public')->delete($attachmentToRemove);
                        unset($currentAttachments[$key]);
                    }
                }
            }
            $currentAttachments = array_values($currentAttachments);
        }

        // Handle new attachment uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $path = $attachment->store('gigs/attachments', 'public');
                $currentAttachments[] = [
                    'path' => $path,
                    'original_name' => $attachment->getClientOriginalName(),
                ];
            }
        }

        $gig->update([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'min_price' => $validated['min_price'],
            'max_price' => $validated['max_price'] ?? null,
            'delivery_days' => $validated['delivery_days'],
            'allows_tutoring' => $request->has('allows_tutoring'),
            'images' => !empty($currentImages) ? $currentImages : null,
            'attachments' => !empty($currentAttachments) ? $currentAttachments : null,
        ]);

        return redirect()->route('gigs.show', $gig)
            ->with('success', 'Gig updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gig $gig)
    {
        // Check if user owns the gig
        if ($gig->seller_id !== Auth::user()->seller->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check for active orders (to be implemented when orders exist)
        // For now, allow deletion

        // Delete associated files
        if ($gig->images) {
            foreach ($gig->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        if ($gig->attachments) {
            foreach ($gig->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $gig->delete();

        return redirect()->route('seller.dashboard')
            ->with('success', 'Gig deleted successfully!');
    }
}
