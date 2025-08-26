<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class EnquiryResponse extends Model
{

    use HasFactory;
    protected $fillable = [
        'Enquiry_id','is_admin','Content'
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

}
