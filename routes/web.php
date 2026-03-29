<?php

use App\Http\Controllers\InstallationObjectController;
use App\Http\Controllers\MeterController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('home');
});

Route::resource('installation-objects', InstallationObjectController::class)
    ->middlewareFor(['store', 'update'], [HandlePrecognitiveRequests::class]);

Route::resource('meters', MeterController::class)
    ->middlewareFor(['store'], [HandlePrecognitiveRequests::class]);
