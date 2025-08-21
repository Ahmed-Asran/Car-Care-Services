<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
        'customer_car_id',
        'provider_id',
        'service_id',
        'location_latitude',
        'location_longitude',
        'distance',
        'service_price',
        'distance_price',
        'total_price',
        'status'
    ];
}
