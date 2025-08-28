<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
        'user_id', 'verification_status', 'national_id_image_id', 'location_id'
    ];

    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requests()
    {
        return $this->hasMany(Requestservice::class);
    }

    
    public function location()
    {
    return $this->belongsTo(Location::class);

    }

    public function nationalIdImage(){
        return $this->belongsTo(Image::class,'national_id_image_id');
    }

}