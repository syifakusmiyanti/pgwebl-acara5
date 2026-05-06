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
route::delete('/delete-points/{id}', [PointsController::class,'destroy'])
->name('points.delete');

// Polylines
route::post('/store-polylines', [PolylinesController::class, 'store'])
    ->name('polylines.store');
route::delete('/delete-polylines/{id}', [PolylinesController::class, 'destroy'])
->name('polylines.delete');

// Polygons
route::post('/store-polygons', [PolygonsController::class, 'store'])
    ->name('polygons.store');
route::delete('/delete-polygons/{id}', [PolygonsController::class, 'destroy'])
->name('polygons.delete');

// Dashboard
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__ . '/settings.php';
