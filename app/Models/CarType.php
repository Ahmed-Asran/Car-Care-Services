<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
 protected $fillable = ['manufacturer', 'model'];

    // use HasFactory;

    public function cars()
    {
        return $this->hasMany(CustomerCar::class);
    }

    public function serviceMappings()
    {
        return $this->hasMany(ServiceForCarType::class);
    }
    
}
