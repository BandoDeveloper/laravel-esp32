<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/', [ApiController::class,'A9gLocation'])->name('A9gLocation');
//SendLocation
Route::get('/sendA9gLocation', [ApiController::class,'sendESPLocation'])->name('sendA9gLocation');
Route::post('/sendA9gLocation', [ApiController::class,'sendESPLocation'])->name('sendA9gLocation');
//Phone location
Route::get('/phoneLocation', [ApiController::class,'phoneLocation'])->name('phoneLocation');
Route::post('/phoneLocation', [ApiController::class,'phoneLocation'])->name('phoneLocation');
//Phone GEO
Route::get('/phoneGeocerca', [ApiController::class,'phoneGeocerca'])->name('phoneGeocerca');
Route::post('/phoneGeocerca', [ApiController::class,'phoneGeocerca'])->name('phoneGeocerca');
//A9G location
Route::get('/a9glocationFromsms', [ApiController::class,'a9glocationFromsms'])->name('a9glocationFromsms');
Route::post('/a9glocationFromsms', [ApiController::class,'a9glocationFromsms'])->name('a9glocationFromsms');
