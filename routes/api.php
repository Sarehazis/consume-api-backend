<?php

use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', RegisterController::class);
Route::post('login', LoginController::class);

Route::apiResource('posts',PostController::class);
Route::apiResource('artikels',ArtikelController::class);

Route::post('logout', LogoutController::class);

