<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'driver_id', 'pickup_location', 'dropoff_location', 'package_details', 'status', 'scheduled_at'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function client()
{
    return $this->belongsTo(\App\Models\User::class, 'client_id');
}

}

