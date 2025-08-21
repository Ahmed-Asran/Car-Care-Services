<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;


/// IMAGE
Route::post('/images', [ImageController::class, 'store']);


///SETTING


















Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
