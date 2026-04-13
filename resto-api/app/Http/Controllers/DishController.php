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
            'name' => 'required|string|min:2',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Enregistre l'image dans le dossier 'dishes' du disque public
            $path = $request->file('image')->store('dishes', 'public');
            $data['image'] = $path;
        }

        $dish = Dish::create($data);
        return response()->json($dish);
    }

    public function show(Dish $dish) {
        return $dish;
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|min:2',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Enregistre l'image dans le dossier 'dishes' du disque public
            $path = $request->file('image')->store('dishes', 'public');
            $data['image'] = $path;
        }
    

        $dish = Dish::findOrFail($id); // Trouve le plat ou renvoie une erreur 404
        $dish->update($data);

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
