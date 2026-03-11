<?php

use App\Http\Controllers\InstallationObjectController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('home');
});

Route::resource('installation-objects', InstallationObjectController::class)
    ->middlewareFor(['update'], [HandlePrecognitiveRequests::class]);
