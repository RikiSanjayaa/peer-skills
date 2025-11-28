<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'gig_id',
        'type',
        'requirements',
        'price',
        'delivery_days',
        'status',
        'seller_notes',
        'quoted_at',
        'accepted_at',
        'delivered_at',
        'completed_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quoted_at' => 'datetime',
        'accepted_at' => 'datetime',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Status constants for easy reference
    const STATUS_PENDING = 'pending';
    const STATUS_QUOTED = 'quoted';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_REVISION_REQUESTED = 'revision_requested';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_DECLINED = 'declined';

    // Type constants
    const TYPE_STANDARD = 'standard';
    const TYPE_TUTORING = 'tutoring';

    /**
     * Relationships
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function gig(): BelongsTo
    {
        return $this->belongsTo(Gig::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(OrderDelivery::class);
    }

    public function tutoringSchedule(): HasOne
    {
        return $this->hasOne(TutoringSchedule::class);
    }

    /**
     * Scopes
     */
    public function scopeForBuyer($query, $userId)
    {
        return $query->where('buyer_id', $userId);
    }

    public function scopeForSeller($query, $userId)
    {
        return $query->where('seller_id', $userId);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            self::STATUS_PENDING,
            self::STATUS_QUOTED,
            self::STATUS_ACCEPTED,
            self::STATUS_DELIVERED,
            self::STATUS_REVISION_REQUESTED,
        ]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Helper methods
     */
    public function isBuyer(User $user): bool
    {
        return $this->buyer_id === $user->id;
    }

    public function isSeller(User $user): bool
    {
        return $this->seller_id === $user->id;
    }

    public function isParticipant(User $user): bool
    {
        return $this->isBuyer($user) || $this->isSeller($user);
    }

    public function isTutoring(): bool
    {
        return $this->type === self::TYPE_TUTORING;
    }

    public function canBeQuoted(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function canBeAccepted(): bool
    {
        return $this->status === self::STATUS_QUOTED;
    }

    public function canBeDeclined(): bool
    {
        return $this->status === self::STATUS_QUOTED;
    }

    public function canBeDelivered(): bool
    {
        return in_array($this->status, [
            self::STATUS_ACCEPTED,
            self::STATUS_REVISION_REQUESTED,
        ]);
    }

    public function canRequestRevision(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function canBeCompleted(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_QUOTED,
        ]);
    }

    public function canAddMessage(): bool
    {
        return !in_array($this->status, [
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
            self::STATUS_DECLINED,
        ]);
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-warning text-dark',
            self::STATUS_QUOTED => 'bg-info text-dark',
            self::STATUS_ACCEPTED => 'bg-primary',
            self::STATUS_DELIVERED => 'bg-success',
            self::STATUS_REVISION_REQUESTED => 'bg-warning text-dark',
            self::STATUS_COMPLETED => 'bg-success',
            self::STATUS_CANCELLED => 'bg-secondary',
            self::STATUS_DECLINED => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Get human-readable status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pending Quote',
            self::STATUS_QUOTED => 'Quote Received',
            self::STATUS_ACCEPTED => 'In Progress',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_REVISION_REQUESTED => 'Revision Requested',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_DECLINED => 'Declined',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get latest delivery
     */
    public function getLatestDeliveryAttribute()
    {
        return $this->deliveries()->latest()->first();
    }
}
