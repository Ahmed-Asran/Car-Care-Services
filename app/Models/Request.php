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

    use HasFactory;

    public function customerCar()
    {
        return $this->belongsTo(CustomerCar::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function statusChanges()
    {
        return $this->hasMany(RequestStatusChange::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
