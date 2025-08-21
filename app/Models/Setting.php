<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Setting extends Model
{

    protected $fillable = [
        'name',
        'logo',
        'about_image',
        'about_description',
        'price_per_km',
        'terms_and_conditions',
        'facebook_url',
        'whatsapp_number',
        'phone_number',
        'second_phone_number',
    ];
}
