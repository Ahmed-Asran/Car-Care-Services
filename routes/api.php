<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\EnquiryResponseController;
use App\Http\Controllers\CustomerCarController;
use App\Http\Controllers\UserController;


/// IMAGE
Route::post('/images', [ImageController::class, 'store']);

Route::post('enquiries', [EnquiryController::class, 'store']);     // POST إضافة استفسار

Route::post('enquiry-responses', [EnquiryResponseController::class, 'store']);

//======================================================
use App\Http\Controllers\CarTypeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceForCarTypeController;
use App\Models\User;

Route::apiResource('car-types', CarTypeController::class);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//-----------------------public routes--------------
//registration 
Route::post('register',[AuthController::class,'register']);
//login
Route::post('login',[AuthController::class,'login']);
//the forget and reset password are public so can be accecss without login
Route::post('forget-password',[UserController::class,'forgetPass']);
 Route::post('/reset-password', [UserController::class, 'reset']);
//-----------------------protected routes-----------
Route::middleware('auth:sanctum')->group(function(){
//request to be provider
Route::post('register/provider',[AuthController::class,'registerProvider']);
//logout
Route::post('logout',[AuthController::class,'logout']);
//Show user profile
Route::get('/users/{user}', [UserController::class, 'show'])
    ->name('users.show');

// Update user profile
Route::post('/users/{user}', [UserController::class, 'update'])
    ->name('users.update');
//Route::post('/reset-password', [AuthController::class, 'reset']);
Route::post('customer-cars', [CustomerCarController::class, 'store']);
Route::get('customer-cars/{id}', [CustomerCarController::class, 'show']);
Route::put('customer-cars/{id}', [CustomerCarController::class, 'update']);
Route::delete('customer-cars/{id}', [CustomerCarController::class, 'destroy']);
Route::get('customer-cars', [CustomerCarController::class, 'index']);
//Route::post('service-create',[ServiceController::class,'store']);
 Route::apiResource('services', ServiceController::class);
 Route::apiResource('service-price',ServiceForCarTypeController::class);
});

