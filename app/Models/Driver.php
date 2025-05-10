<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Driver extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'vehicle_type',
        'plate_number',
        'pricing_model',
        'price',
        'fcm_token',
        'scheduled_at'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];
}
