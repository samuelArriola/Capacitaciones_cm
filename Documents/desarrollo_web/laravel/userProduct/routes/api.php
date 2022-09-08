<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Login;
use App\Http\Controllers\ProducController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user', [UserController::class, 'store']);
Route::post('login', [Login::class , 'login']);

Route::group([

    'middleware' =>  ['jwt.auth'],
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'

], function ($router) {
    
    Route::post('logout', [Login::class, 'logout']);
    Route::post('refresh', [Login::class , 'refresh']);
    Route::post('me', [Login::class , 'me']);
    
    Route::get('user', [UserController::class, 'index']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::delete('user/{id}', [UserController::class, 'destroy']);

    Route::post('produc', [ProducController::class, 'store']);
    Route::get('produc', [ProducController::class, 'index']);
    Route::delete('produc', [ProducController::class, 'destroy']);
    Route::delete('produc/{id}', [ProducController::class, 'destroyParam']);
});