<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCar extends Model
{
    protected $fillable = [
        'customer_id',
        'car_type_id',
        'car_license',
    ];
}
