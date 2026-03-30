<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\PolylinesController;
use App\Http\Controllers\PolygonsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/peta', PageController::class . '@peta')->name('peta');

Route::get('/tabel', function () {
    return view('table');
})->name('tabel');


// Points
route::post('/store-points', [PointsController::class, 'store'])
    ->name('points.store');

// Polylines
route::post('/store-polylines', [PolylinesController::class, 'store'])
    ->name('polylines.store');

// Polygons
route::post('/store-polygons', [PolygonsController::class, 'store'])
    ->name('polygons.store');

// Dashboard
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__ . '/settings.php';
