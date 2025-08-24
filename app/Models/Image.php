<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'mime', 'path'];
    public function provider(){
        return $this->hasOne(provider::class,'national_id_image_id');
    } 
}
