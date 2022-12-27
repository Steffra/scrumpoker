<?php

use App\Http\Controllers\PokerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/room/{id?}', [PokerController::class, 'room']);
Route::post('/rooms', [PokerController::class, 'rooms']);
Route::get('/toggleRoom/{id?}', [PokerController::class, 'toggleRoom']);
Route::post('/createroom', [PokerController::class, 'createroom']);
Route::get('/createplayer/{name?}', [PokerController::class, 'createplayer']);
Route::post('/joinroom', [PokerController::class, 'joinroom']);
Route::post('/setPlayerCardValue', [PokerController::class, 'setPlayerCardValue']);
