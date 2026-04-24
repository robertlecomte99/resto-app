<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
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
        // 1. Validation des données entrantes
        $validated = $request->validate([
            'dish_id' => 'required|exists:dishes,id',
            'menu_id' => 'required|exists:menus,id',
            // Plus sécurisé : on prend l'ID depuis le token d'auth
        ]);

        $todayMenu = Menu::where('menu_date', now()->toDateString())->first();

        if (!$todayMenu) {
            return response()->json(['message' => "Aucun menu n'est publié pour aujourd'hui."], 404);
        }

        $user = $request->user(); // Récupère l'utilisateur connecté via Sanctum

        // 2. Vérification de la limite de 2 commandes par utilisateur pour ce menu
        $orderCount = Order::where('user_id', $user->id)
                            ->where('menu_id', $todayMenu->id)
                            ->count();

        if ($orderCount >= 2) {
            return response()->json(['message' => "Vous avez atteint votre limite de 2 commandes pour aujourd'hui."], 429);
        }

        // 3. Creation de la commande
        $order = Order::create([
            'dish_id' => $validated['dish_id'],
            'menu_id' => $todayMenu->id, // On force le menu du jour
            'user_id' => $user->id // On force l'utilisateur authentifié
        ]);

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
