<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['request_id', 'rating', 'comment'];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
