<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
        'user_id', 'verification_status', 'national_id_image_id', 'location_id'
    ];
}