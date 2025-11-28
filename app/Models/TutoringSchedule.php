<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TutoringSchedule extends Model
{
  use HasFactory;

  protected $fillable = [
    'order_id',
    'proposed_slots',
    'confirmed_slot',
    'external_link',
    'topic',
  ];

  protected $casts = [
    'proposed_slots' => 'array',
    'confirmed_slot' => 'datetime',
  ];

  public function order(): BelongsTo
  {
    return $this->belongsTo(Order::class);
  }

  /**
   * Check if a slot has been confirmed
   */
  public function isConfirmed(): bool
  {
    return !is_null($this->confirmed_slot);
  }

  /**
   * Get formatted confirmed slot
   */
  public function getFormattedConfirmedSlotAttribute(): ?string
  {
    if (!$this->confirmed_slot) {
      return null;
    }

    return $this->confirmed_slot->format('l, F j, Y \a\t g:i A');
  }

  /**
   * Check if the session is in the past
   */
  public function isPast(): bool
  {
    return $this->confirmed_slot && $this->confirmed_slot->isPast();
  }

  /**
   * Check if the session is upcoming (within 24 hours)
   */
  public function isUpcoming(): bool
  {
    if (!$this->confirmed_slot) {
      return false;
    }

    return $this->confirmed_slot->isFuture() &&
      $this->confirmed_slot->diffInHours(now()) <= 24;
  }
}
