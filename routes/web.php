<?php

use App\Http\Controllers\InstallationObjectController;
use App\Http\Controllers\InstallationObjectMeterController;
use App\Http\Controllers\MeterController;
use App\Http\Controllers\SimCardController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('home');
});

Route::resource('installation-objects', InstallationObjectController::class)
    ->middlewareFor(['store', 'update'], [HandlePrecognitiveRequests::class]);

Route::resource('meters', MeterController::class)
    ->middlewareFor(['store', 'update'], [HandlePrecognitiveRequests::class]);

Route::resource('installation-objects.meters', InstallationObjectMeterController::class)
    ->only(['create', 'store', 'destroy']);

Route::resource('sim-cards', SimCardController::class)
    ->middlewareFor(['store', 'update'], [HandlePrecognitiveRequests::class]);
