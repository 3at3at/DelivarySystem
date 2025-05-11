<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Driver extends Authenticatable
{
    use Notifiable;

protected $fillable = [
    'name', 'email', 'phone', 'password',
    'vehicle_type', 'plate_number',
    'pricing_model', 'price',
    'working_hours', 'location', 'is_available',
    'scheduled_at', 'fcm_token'
];


    protected $hidden = ['password'];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];
}
