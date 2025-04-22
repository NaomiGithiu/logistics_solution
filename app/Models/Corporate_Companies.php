<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corporate_Companies extends Model
{
    use HasFactory;

    protected $table = 'corporate_companies';

    protected $fillable = [
            'name',
            'corporate_id',
            'corporate_email',
            'contact_person',
            'contact',
            'address'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

}
