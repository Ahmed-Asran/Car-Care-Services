<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class service extends Model
{
    protected $fillable = [
        'name',
    ];

    use HasFactory;

    public function serviceMappings()
    {
        return $this->hasMany(ServiceForCarType::class);
    }

    
}