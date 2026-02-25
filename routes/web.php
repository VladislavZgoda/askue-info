<?php

use App\Http\Controllers\InstallationObjectController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::resource('installation-objects', InstallationObjectController::class);
