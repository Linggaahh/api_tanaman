<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TanamanController;
use App\Http\Controllers\KandangController;
use App\Http\Controllers\PakanController;
use App\Http\Controllers\PanenController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\PengirimanController;

// User Routes
Route::get('/user', [UserController::class, 'index']);
Route::get('/user/{id}', [UserController::class, 'show']);
Route::post('/user', [UserController::class, 'store']);
Route::put('/user/{id}', [UserController::class, 'update']); // UBAH JADI PUT
Route::post('/user/upload-profile-picture', [UserController::class, 'uploadProfilePicture']); // TAMBAHKAN INI
Route::delete('/user/{id}', [UserController::class, 'destroy']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/logout', [UserController::class, 'logout']);

// Tanaman Routes
Route::get('/tanaman', [TanamanController::class, 'index']);
Route::get('/tanaman/{id}', [TanamanController::class, 'show']);
Route::post('/tanaman', [TanamanController::class, 'store']);
Route::put('/tanaman/{id}', [TanamanController::class, 'update']);
Route::delete('/tanaman/{id}', [TanamanController::class, 'destroy']);
Route::post('/tanaman/{id}/panen', [TanamanController::class, 'panen']);

// Kandang Routes
Route::get('/kandang', [KandangController::class, 'index']);
Route::get('/kandang/{id}', [KandangController::class, 'show']);
Route::post('/kandang', [KandangController::class, 'store']);
Route::put('/kandang/{id}', [KandangController::class, 'update']);
Route::delete('/kandang/{id}', [KandangController::class, 'destroy']);

// Pakan Routes
Route::get('/pakan', [PakanController::class, 'index']);
Route::get('/pakan/{id}', [PakanController::class, 'show']);
Route::post('/pakan', [PakanController::class, 'store']);
Route::put('/pakan/{id}', [PakanController::class, 'update']);
Route::delete('/pakan/{id}', [PakanController::class, 'destroy']);

// Panen Routes
Route::get('/panen', [PanenController::class, 'index']);
Route::get('/panen/{id}', [PanenController::class, 'show']);
Route::post('/panen', [PanenController::class, 'store']);
Route::put('/panen/{id}', [PanenController::class, 'update']);
Route::delete('/panen/{id}', [PanenController::class, 'destroy']);

// Supply Routes
Route::get('/supply', [SupplyController::class, 'index']);
Route::get('/supply/{id}', [SupplyController::class, 'show']);
Route::post('/supply', [SupplyController::class, 'store']);
Route::put('/supply/{id}', [SupplyController::class, 'update']);
Route::delete('/supply/{id}', [SupplyController::class, 'destroy']);

// Sensor Routes
Route::get('/sensor', [SensorController::class, 'index']);
Route::get('/sensor/{id}', [SensorController::class, 'show']);
Route::post('/sensor', [SensorController::class, 'store']);
Route::put('/sensor/{id}', [SensorController::class, 'update']);
Route::delete('/sensor/{id}', [SensorController::class, 'destroy']);

// Pengiriman Routes
Route::get('/pengiriman', [PengirimanController::class, 'index']);
Route::get('/pengiriman/{id}', [PengirimanController::class, 'show']);
Route::post('/pengiriman', [PengirimanController::class, 'store']);
Route::put('/pengiriman/{id}', [PengirimanController::class, 'update']);
Route::delete('/pengiriman/{id}', [PengirimanController::class, 'destroy']);

// Test Route
Route::get('/test', function () {
    return response()->json(['message' => 'API Berhasil Terhubung!']);
});