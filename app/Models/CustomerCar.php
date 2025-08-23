<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class CustomerCar extends Model
{
    protected $fillable = [
        'customer_id',
        'car_type_id',
        'car_license',
    ];

    use HasFactory;

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function carType()
    {
        return $this->belongsTo(CarType::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

}
