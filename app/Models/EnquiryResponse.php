<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnquiryResponse extends Model
{
    protected $fillable = [
        'Enquiry_id','is_admin','Content'
    ];
}
