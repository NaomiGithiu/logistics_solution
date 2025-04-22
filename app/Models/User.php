<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Corporate_Companies;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class User extends Authenticatable implements CanResetPasswordContract
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, CanResetPassword;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'password',
        'role',
        'corporate_id',          
        'is_corporate_admin', 
        'must_change_password'
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function trips()
    {
        return $this->hasMany(Trip::class, 'driver_id');
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id'); 
    }
    public function assignedTrips()
    {
        return $this->hasMany(Trip::class, 'driver_id');
    } 
    
    public function corporate()
    {
        return $this->belongsTo(Corporate_Companies::class, 'corporate_id');
    }
        

}
