<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/', function () {
    return view('index');
});
Route::get('/verDatosSensor', [ApiController::class,'verDatosSensor'])->name('verDatosSensor');
Route::post('/verDatosSensor', [ApiController::class,'verDatosSensor'])->name('verDatosSensor');
Route::get('/obtenerEstadoLed', [ApiController::class,'obtenerEstadoLed'])->name('obtenerEstadoLed');
Route::post('/obtenerEstadoLed', [ApiController::class,'obtenerEstadoLed'])->name('obtenerEstadoLed');
Route::get('/toggleLed', [ApiController::class,'switchToggleStatus'])->name('toggleLed');
Route::post('/toggleLed', [ApiController::class,'switchToggleStatus'])->name('toggleLed');
