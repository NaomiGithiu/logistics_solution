<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'driver_id',
        'pickup_location',
        'dropoff_location',
        'estimated_fare',
        'vehicle_type',
        'scheduled_time',
        'status',
        'weight',
        'canceled_by',
        'cancel_reason',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
    
        public function trip()
    {
        return $this->hasOne(Trip::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
