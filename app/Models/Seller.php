<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seller extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
        'description',
        'major',
        'university',
        'portfolio_url',
        'skills',
        'is_active',
    ];

    protected $casts = [
        'skills' => 'array',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
