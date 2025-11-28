<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // Menampilkan daftar kategori
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }
    // 1. Menampilkan Form Tambah
    public function create()
    {
        return view('admin.categories.create');
    }

    // 2. Menyimpan Data ke Database
    public function store(Request $request)
{
    // 1. Update Validasi (tambahkan description)
    $request->validate([
        'name' => 'required|string|max:255|unique:categories,name',
        'description' => 'required|string|min:10', // Boleh diisi, boleh kosong
    ]);

    // 2. Simpan ke database (tambahkan description)
    Category::create([
        'name' => $request->name,
        'slug' => Str::slug($request->name),
        'description' => $request->description, // <--- INI TAMBAHANNYA
    ]);

    return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
}
// 3. Menampilkan Form Edit (Ambil data lama)
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // 4. Proses Update Data
    public function update(Request $request, Category $category)
    {
        // Validasi
        $request->validate([
            // unique:categories,name,ID_NYA -> artinya "Nama harus unik, KECUALI untuk kategori ini sendiri"
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'required|string|min:10',
        ]);

        // Update Database
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    // 5. Proses Hapus Data
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
    // Nanti kita tambahkan fitur Create, Edit, Delete di sini
}