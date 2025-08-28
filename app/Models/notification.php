<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notification extends Model
{
        protected $fillable = [
        'request_id',
        'content',
    ];

    use HasFactory;

    public function request()
    {
        return $this->belongsTo(RequestService::class);
    }


}
