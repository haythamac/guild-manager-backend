<?php

use App\Http\Controllers\GuildController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\SessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/test', function () {
    return response()->json(['message' => 'Hello World!']);
});

Route::post('login', [SessionController::class, 'login']); 
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [SessionController::class, 'logout']); 
});

Route::apiResource('players', PlayerController::class);
Route::post('verifyPlayer', [PlayerController::class, 'verifyPlayer']);
Route::put('playerUpdate/{player:ign}', [PlayerController::class, 'playerUpdate']);



Route::apiResource('guilds', GuildController::class);
Route::get('guilds/{guildName}/distinctClassPerGuild', [PlayerController::class, 'distinctClassPerGuild']);



