<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
  use HasFactory;

  protected $fillable = [
    'order_id',
    'reviewer_id',
    'seller_id',
    'gig_id',
    'rating',
    'comment',
  ];

  protected $casts = [
    'rating' => 'integer',
  ];

  /**
   * Get the order this review belongs to
   */
  public function order(): BelongsTo
  {
    return $this->belongsTo(Order::class);
  }

  /**
   * Get the reviewer (buyer)
   */
  public function reviewer(): BelongsTo
  {
    return $this->belongsTo(User::class, 'reviewer_id');
  }

  /**
   * Get the seller being reviewed
   */
  public function seller(): BelongsTo
  {
    return $this->belongsTo(Seller::class);
  }

  /**
   * Get the gig being reviewed
   */
  public function gig(): BelongsTo
  {
    return $this->belongsTo(Gig::class);
  }
}
