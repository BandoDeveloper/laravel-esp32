<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/', function () {
    return view('index');
});
Route::get('/verDatosSensor', [ApiController::class,'verDatosSensor'])->name('verDatosSensor');
Route::post('/obtenerEstadoLed', [ApiController::class,'obtenerEstadoLed'])->name('obtenerEstadoLed');
