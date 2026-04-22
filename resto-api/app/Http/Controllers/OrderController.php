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
        return Order::with(['dish', 'menu','user'])->orderBy('created_at', 'desc')->get();

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

        $todayMenu = Menu::where('menu_date', now()->toDateString())->first();

        if (!$todayMenu) {
            return response()->json(['message' => "Aucun menu n'est publié pour aujourd'hui."], 404);
        }

        // Vérification de la contrainte : 1 commande max par employé 
        $alreadyOrdered = Order::where('user_id', $user->id)
                            ->where('menu_id', $todayMenu->id)
                            ->exists();

        if ($alreadyOrdered) {
            return response()->json(['message' => "Vous avez déjà passé votre commande pour aujourd'hui."], 429);
        }


        // 1. Validation :
        $validated = $request->validate([
            'dish_id' => 'required|exists:dishes,id',
            'menu_id' => 'required|exists:menus,id',
            'user_id' => 'required|exists:users,id'
        ]);


        // 2. Creation de la commande
        $order = Order::create([
            'dish_id' => $validated['dish_id'],
            'menu_id' => $validated['menu_id'],
            'user_id' => $validated['user_id']
        ]);

        // 3. Retourne la commande creee avec un code 201 (Created)
        return response()->json([
            'message' => 'Commande réussie !',
            'order' => $order->load('dish')
        ], 201);
    }

    
    public function show(Order $order)
    {
        //
    }

   
    public function edit(Order $order)
    {
        //
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string'
        ]);

        $order->update($validated);

        return response()->json($order);
    }

    
    public function destroy(Order $order)
    {
        //
    }
}
