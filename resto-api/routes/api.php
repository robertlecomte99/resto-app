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

// --- ROUTES PROTÉGÉES (token requis) ---
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/orders', [OrderController::class, 'index']); // Voir les commandes
    Route::post('/orders', [OrderController::class, 'store']); // Commander

    Route::get('/menus/current', [MenuController::class, 'currentMenu']); //menu du jour pour les employés
    
    
    Route::apiResource('menus', MenuController::class);//menus
    Route::apiResource('dishes', DishController::class);// Gestion des plats (Ajout, Modif, Suppr)
    Route::put('/orders/{order}', [OrderController::class, 'update']); // modif le statut

});