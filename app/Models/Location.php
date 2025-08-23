<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'street', 'city', 'state', 'country', 'latitude', 'longitude'
    ];

    public function providers()
    {
        return $this->hasMany(Provider::class);
    }
}
