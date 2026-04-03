<?php

use App\Http\Controllers\DishController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// --- ROUTES PUBLIQUES ---
Route::post('/login', [AuthController::class, 'login']);
Route::get('/dishes', [DishController::class, 'index']); // Voir le menu
Route::post('/orders', [OrderController::class, 'store']); // Commander

// --- ROUTES PROTÉGÉES (Admin uniquement) ---
Route::middleware('auth:sanctum')->group(function () {
    // Gestion des plats (Ajout, Modif, Suppr)
    Route::apiResource('dishes', DishController::class)->except(['index']);
    
    // Gestion du flux de commandes
    Route::get('/orders', [OrderController::class, 'index']); // Voir les commandes
    Route::put('/orders/{order}', [OrderController::class, 'update']); // modif le statut
});