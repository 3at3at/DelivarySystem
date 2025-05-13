<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
 protected $fillable = [
    'order_id',
    'client_id',
    'driver_id',
    'pickup_location',
    'dropoff_location',
    'package_type',
    'package_weight',
    'package_dimensions',
    'urgency',
    'status',
    'price',

];


    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function client()
{
    return $this->belongsTo(\App\Models\User::class, 'client_id');
}
public function order()
{
    return $this->belongsTo(Order::class);
}

}

