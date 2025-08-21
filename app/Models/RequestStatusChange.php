<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestStatusChange extends Model
{
    protected $fillable = [
        'request_id',
        'status'
    ];
}

