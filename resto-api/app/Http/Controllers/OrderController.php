<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //On récupère les commandes avec les infos du plat associé 
        return Order::with('dish')->orderBy('created_at', 'desc')->get();

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation : on verifie que dish_id est envoye et existe dans la table dishes
        $validated = $request->validate([
            'dish_id' => 'required|exists:dishes,id',
        ]);

        // 2. Creation de la commande
        $order = Order::create([
            'dish_id' => $validated['dish_id']
        ]);

        // 3. Retourne la commande creee avec un code 201 (Created)
        return response()->json([
            'message' => 'Commande réussie !',
            'order' => $order->load('dish')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string'
        ]);

        $order->update($validated);

        return response()->json($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
