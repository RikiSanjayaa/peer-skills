<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends Model
{
    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_SUSPENDED = 'suspended';

    // Predefined rejection reasons
    const REJECTION_REASONS = [
        'incomplete_description' => 'Deskripsi bisnis tidak lengkap atau tidak jelas',
        'invalid_portfolio' => 'Link portfolio tidak dapat diakses atau tidak valid',
        'irrelevant_skills' => 'Keahlian tidak relevan dengan layanan yang ditawarkan',
        'invalid_university' => 'Informasi universitas tidak dapat diverifikasi',
        'duplicate_account' => 'Terdeteksi akun duplikat',
        'inappropriate_content' => 'Konten tidak sesuai dengan kebijakan platform',
        'other' => 'Alasan lainnya',
    ];

    protected $fillable = [
        'user_id',
        'business_name',
        'description',
        'major',
        'university',
        'portfolio_url',
        'skills',
        'is_active',
        'status',
        'rejection_reason',
        'rejected_at',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'skills' => 'array',
        'is_active' => 'boolean',
        'rejected_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gigs(): HasMany
    {
        return $this->hasMany(Gig::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // Helpers
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-warning text-dark',
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
            self::STATUS_SUSPENDED => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Menunggu Review',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_SUSPENDED => 'Ditangguhkan',
            default => ucfirst($this->status),
        };
    }
}
