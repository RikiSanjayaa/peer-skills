<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class OrderDelivery extends Model
{
  use HasFactory;

  protected $fillable = [
    'order_id',
    'file_path',
    'file_name',
    'message',
    'is_final',
  ];

  protected $casts = [
    'is_final' => 'boolean',
  ];

  public function order(): BelongsTo
  {
    return $this->belongsTo(Order::class);
  }

  /**
   * Get the download URL for the file
   */
  public function getFileUrlAttribute(): ?string
  {
    if (!$this->file_path) {
      return null;
    }

    return Storage::url($this->file_path);
  }

  /**
   * Check if this delivery has a file
   */
  public function hasFile(): bool
  {
    return !empty($this->file_path);
  }
}
