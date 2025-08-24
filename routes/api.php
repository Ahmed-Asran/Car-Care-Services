<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\EnquiryResponseController;
use App\Http\Controllers\CustomerCarController;


/// IMAGE
Route::post('/images', [ImageController::class, 'store']);

Route::post('enquiries', [EnquiryController::class, 'store']);     // POST إضافة استفسار

Route::post('enquiry-responses', [EnquiryResponseController::class, 'store']);


Route::get('customer-cars', [CustomerCarController::class, 'index']);
Route::get('customer-cars/{id}', [CustomerCarController::class, 'show']);
Route::post('customer-cars', [CustomerCarController::class, 'store']);
Route::put('customer-cars/{id}', [CustomerCarController::class, 'update']);
Route::delete('customer-cars/{id}', [CustomerCarController::class, 'destroy']);


use App\Http\Controllers\CarTypeController;

Route::apiResource('car-types', CarTypeController::class);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//-----------------------public routes--------------
//registration 
Route::post('register',[AuthController::class,'register']);
//login
Route::post('login',[AuthController::class,'login']);
//-----------------------protected routes-----------
Route::middleware('auth:sanctum')->group(function(){
//request to be provider
Route::post('register/provider',[AuthController::class,'registerProvider']);
//logout
Route::post('logout',[AuthController::class,'logout']);
});

