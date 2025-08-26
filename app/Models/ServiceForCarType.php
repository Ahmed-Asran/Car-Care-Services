<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ServiceForCarType extends Model
{
    protected $table='service_car_type_pricing';
    protected $fillable=[
        'service_id',
        'car_type_id',
        'price',
    ];

    use HasFactory;

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function carType()
    {
        return $this->belongsTo(CarType::class);
    }
}
