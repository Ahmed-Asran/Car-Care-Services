<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model {
    protected $fillable = ['user_id', 'verification_status', 'national_id_image', 'location_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function location() {
        return $this->belongsTo(Location::class);
    }
}