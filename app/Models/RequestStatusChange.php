<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestStatusChange extends Model
{
    public $timestamps = true;   
        const UPDATED_AT = null;     // disable updated_at
    protected $fillable = [
        'request_id',
        'status'
    ];

    use HasFactory;

    public function request()
    {
        return $this->belongsTo(RequestService::class, 'request_id');
    }

}

