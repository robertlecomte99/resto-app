<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function index() {
        return Dish::withCount('orders')->get();
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric'
        ]);
        return Dish::create($data);
    }

    public function show(Dish $dish) {
        return $dish;
    }

    public function update(Request $request, $id)
    {
        $dish = Dish::findOrFail($id); // Trouve le plat ou renvoie une erreur 404
        $dish->update($request->all());

        return response()->json($dish);
    }

    public function destroy(Dish $dish) {
        $dish->delete();
        return response()->json(['message' => 'Supprimé']);
    }

    // Nombre de commandes par plat
    public function stats() {
        return Dish::withCount('orders')->get();
    }
}
