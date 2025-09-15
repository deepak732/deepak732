<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\HotelController;
use App\Http\Controllers\Api\V1\BookingController;

Route::prefix('v1')->group(function () {
    Route::get('/hotels', [HotelController::class, 'index']);
    Route::get('/hotels/{hotel}', [HotelController::class, 'show']);
    Route::post('/bookings', [BookingController::class, 'store']);
});

