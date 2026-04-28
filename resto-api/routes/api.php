<?php

use App\Http\Controllers\DishController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// --- ROUTES PUBLIQUES ---
Route::post('/login', [AuthController::class, 'login']);

// Routes pour les employés (ou les deux)
Route::middleware(['auth:sanctum', 'role:employee|admin'])->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/menus/current', [MenuController::class, 'currentMenu']);
});

// --- ROUTES PROTÉGÉES ADMIN---
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    // Commandes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/today', [OrderController::class, 'todayOrders']);
    Route::get('/orders/daily-stats', [OrderController::class, 'dailyStats']);

    // Menus
    
    Route::apiResource('menus', MenuController::class);

    // Plats
    Route::apiResource('dishes', DishController::class);

    Route::put('/orders/{order}', [OrderController::class, 'update']);
    Route::delete('/orders/{order}', [OrderController::class, 'destroy']);
});


