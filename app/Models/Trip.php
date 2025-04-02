<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'driver_id',
        'pickup_location',
        'dropoff_location',
        'vehicle_type',
        'status',
        'cancel_reason',
        'scheduled_time',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id'); 
    }
    
}

