<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\SensorController;
use App\Http\Controllers\Api\SummaryController;
use App\Http\Controllers\Api\VisitorController;

/*********************
 * API Routes
 *********************/

Route::get('/locations', [LocationController::class, 'index']);
Route::post('/locations', [LocationController::class, 'store']);

Route::get('/sensors', [SensorController::class, 'index']);
Route::post('/sensors', [SensorController::class, 'store']);

Route::get('/visitors', [VisitorController::class, 'index']);
Route::post('/visitors', [VisitorController::class, 'store']);

Route::get('/summary', [SummaryController::class, 'index']);