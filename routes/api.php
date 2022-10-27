<?php

use App\Http\Controllers\API\AbsensiController;
use App\Http\Controllers\API\JadwalController;
use App\Http\Controllers\API\KeluhanController;
use App\Http\Controllers\API\PerbaikanController;
use App\Http\Controllers\API\RatingController;
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

// Keluhan
Route::post('keluhanCreate', [KeluhanController::class, 'createKeluhan']);
Route::get('getKeluhan', [KeluhanController::class, 'getKeluhan']);
Route::post('updateKeluhan/{id}', [KeluhanController::class, 'updateKeluhan']);
Route::post('deleteKeluhan/{id}', [KeluhanController::class, 'deleteKeluhan']);

// Perbaikan
Route::post('createPerbaikan', [PerbaikanController::class, 'createPerbaikan']);
Route::get('getPerbaikan', [PerbaikanController::class, 'getPerbaikan']);
Route::post('updatePerbaikan/{id}', [PerbaikanController::class, 'updatePerbaikan']);
Route::post('deletePerbaikan/{id}', [PerbaikanController::class, 'deletePerbaikan']);

// RATING
Route::post('createRating', [RatingController::class, 'createRate']);
Route::post('updateRating/{id}', [RatingController::class, 'updateRate']);
Route::post('deleteRating/{id}', [RatingController::class, 'deleteRate']);
Route::get('getRating', [RatingController::class, 'getAllRate']);

// Absensi
Route::post('createAbsensi', [AbsensiController::class, 'addAbsensi']);
Route::get('getAbsensi', [AbsensiController::class, 'getAbsensi']);
Route::post('updateAbsensi/{id}', [AbsensiController::class, 'updateAbsensi']);
Route::post('deleteAbsensi/{id}', [AbsensiController::class, 'deleteAbsensi']);

// Jadwal
Route::get('getJadwal', [JadwalController::class, 'getJadwal']);
Route::post('updateJadwal/{id}', [JadwalController::class, 'updateJadwal']);
Route::post('deleteJadwal/{id}', [JadwalController::class, 'deleteJadwal']);


