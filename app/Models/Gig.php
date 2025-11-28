<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gig extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'category_id',
        'title',
        'description',
        'min_price',
        'max_price',
        'delivery_days',
        'allows_tutoring',
        'images',
        'attachments',
    ];

    protected $casts = [
        'images' => 'array',
        'attachments' => 'array',
        'allows_tutoring' => 'boolean',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
