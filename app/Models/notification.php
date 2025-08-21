<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class notification extends Model
{
        protected $fillable = [
        'request_id',
        'content',
    ];


}
