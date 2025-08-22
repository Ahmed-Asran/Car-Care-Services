<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\EnquiryResponseController;


/// IMAGE
Route::post('/images', [ImageController::class, 'store']);

Route::post('enquiries', [EnquiryController::class, 'store']);     // POST إضافة استفسار

Route::post('enquiry-responses', [EnquiryResponseController::class, 'store']);

///SETTING


















Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
