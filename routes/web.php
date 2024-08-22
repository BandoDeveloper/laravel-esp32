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
Route::get('/EspLocation', [ApiController::class,'EspLocation'])->name('EspLocation');
Route::post('/LocationFromDB', [ApiController::class,'LocationFromDB'])->name('LocationFromDB');
Route::get('/LocationFromDB', [ApiController::class,'LocationFromDB'])->name('LocationFromDB');
//ENVIAR DATOS DEL DHT
Route::get('/sendDHTData', [ApiController::class,'sendDHTData'])->name('sendDHTData');
Route::post('/sendDHTData', [ApiController::class,'sendDHTData'])->name('sendDHTData');
Route::get('/sendESPLocation', [ApiController::class,'sendESPLocation'])->name('sendESPLocation');
Route::post('/sendESPLocation', [ApiController::class,'sendESPLocation'])->name('sendESPLocation');
