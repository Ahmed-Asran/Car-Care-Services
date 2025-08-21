<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class serviceforCarType extends Model
{
    protected $fillable=[
        'service_id',
        'car_type_id',
        'price',
    ];
}
