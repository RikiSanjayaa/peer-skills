<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'order_id', 'gig_id', 'reviewer_id', 'seller_id', 'rating', 'comment'
    ];

    // Relasi ke User (Pembeli yang nulis review)
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // Relasi ke Gig
    public function gig()
    {
        return $this->belongsTo(Gig::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Fitur Tambahan: Hitung rata-rata rating otomatis
    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0; // Kalau belum ada review, return 0
    }
}