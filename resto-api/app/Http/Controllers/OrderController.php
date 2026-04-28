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
    // Vérification de l'heure (Deadline 11h30)
    if (now()->format('H:i') > '19:30') {
        return response()->json(['message' => "L'heure limite de commande (11h30) est dépassée."], 403);
    }

    $todayMenu = Menu::where('menu_date', now()->toDateString())->first();
    if (!$todayMenu) {
        return response()->json(['message' => "Aucun menu n'est publié pour aujourd'hui."], 404);
    }

    $userId = auth()->id(); // Utilisation de l'ID de l'utilisateur authentifié
    
    // Vérification de la limite de 2 commandes par jour
    $count = Order::where('user_id', $userId)->where('menu_id', $todayMenu->id)->count();
    if ($count >= 1) {
        return response()->json(['message' => "Limite de commande par jour atteinte."], 429);
    }

    $validated = $request->validate([
        'dish_id' => 'required|exists:dishes,id',
    ]);

    $order = Order::create([
        'dish_id' => $validated['dish_id'],
        'menu_id' => $todayMenu->id,
        'user_id' => $userId
    ]);

    return response()->json(['message' => 'Commande réussie !', 'order' => $order->load('dish')], 201);
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

    public function checkStatus(Request $request) {
        $hasOrdered = Order::where('user_id', $request->user()->id)
                        ->where('menu_id', $request->query('menu_id'))
                        ->exists();
                        
        return response()->json(['hasOrdered' => $hasOrdered]);
    }

    public function adminDashboard()
    {
        $today = now()->toDateString();
        
        return response()->json([
            'total_orders_today' => Order::whereDate('created_at', $today)->count(),
            'orders_by_employee' => Order::with(['user', 'dish'])
                ->whereDate('created_at', $today)
                ->get()
                ->groupBy('user.name')
        ]);
    }
}
