<?php

use App\Http\Controllers\PlayerController;
use App\Http\Controllers\SessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/hello', function() {
    return response()->json(['message' => 'Hello World!']);
});

Route::options('/cors-test', function () {
    return response()->json(['message' => 'CORS test successful']);
});

Route::get('/cors-test', function () {
    return response()->json(['message' => 'CORS test successful']);
});

Route::post('login', [SessionController::class, 'login']); 
Route::apiResource('players', PlayerController::class);
Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [SessionController::class, 'logout']); 
});