<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'order_id', 'reporter_id', 'reported_user_id', 
        'reason', 'description', 'status'
    ];

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke User (Pelapor)
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    // Relasi ke User (Terlapor)
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }
}