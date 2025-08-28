<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestService extends Model
{
    protected $table = 'requests';
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
        return $this->hasMany(RequestStatusChange::class,'request_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
    protected static function boot()
    {
         parent::boot();
        static::created(function ($requestService) {
            // Create an initial status change record when a new request is created
            $requestService->statusChanges()->create([
                'status' => $requestService->status,
            ]);
        });
        static::updated(function ($requestService) {
            // Update the status change record when the status is updated
           if($requestService->isDirty('status')) {
               $requestService->statusChanges()->create([
                   'status' => $requestService->status,
               ]);
           }
        });
    }
}
