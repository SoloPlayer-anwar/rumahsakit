<?php

use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\SistemController;
use App\Http\Controllers\API\SupplyController;
use App\Http\Controllers\API\UserController;
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

Route::middleware('auth:sanctum')->group(function(){
    Route::post('logout', [UserController::class, 'logout']);
});

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::post('update/{id}', [UserController::class, 'updateUser']);
Route::get('userList', [UserController::class, 'userList']);

Route::get('get/{id}', [UserController::class, 'getUser']);
Route::post('delete/{id}', [UserController::class, 'deleteUser']);

// Room
Route::post('createRoom', [RoomController::class, 'createRoom']);
Route::get('getRoom', [RoomController::class, 'getRoom']);
Route::post('updateRoom/{id}', [RoomController::class, 'updateRoom']);
Route::post('deleteRoom/{id}', [RoomController::class, 'deleteRoom']);


// Supply
Route::post('createSupply', [SupplyController::class, 'createSupply']);
Route::get('getSupply', [SupplyController::class, 'getSupply']);
Route::post('updateSupply/{id}', [SupplyController::class, 'updateSupply']);
Route::post('deleteSupply/{id}', [SupplyController::class, 'deleteSupply']);

