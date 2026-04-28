<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with(['dish', 'menu', 'user'])->orderBy('created_at', 'desc')->get();
    }

    /**
     * Commandes du jour avec infos employé.
     */
    public function todayOrders()
    {
        $todayMenu = Menu::where('menu_date', now()->toDateString())->first();

        if (!$todayMenu) {
            return response()->json([]);
        }

        return Order::with(['dish', 'user'])
            ->where('menu_id', $todayMenu->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Statistiques de commandes par jour (30 derniers jours).
     */
    public function dailyStats()
    {
        $stats = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($stats);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dish_id' => 'required|exists:dishes,id',
            'menu_id' => 'required|exists:menus,id',
        ]);

        $todayMenu = Menu::where('menu_date', now()->toDateString())->first();

        if (!$todayMenu) {
            return response()->json(['message' => "Aucun menu n'est publié pour aujourd'hui."], 404);
        }

        // Vérification deadline
        $deadline = $todayMenu->order_deadline ?? '11:30';
        if (Carbon::now()->format('H:i') > $deadline) {
            return response()->json([
                'message' => "Les commandes sont fermées depuis {$deadline}.",
                'deadline_passed' => true,
            ], 403);
        }

        $user = $request->user();

        $orderCount = Order::where('user_id', $user->id)
                           ->where('menu_id', $todayMenu->id)
                           ->count();

        if ($orderCount >= 20) {
            return response()->json(['message' => "Vous avez atteint votre limite de commandes pour aujourd'hui."], 429);
        }

        $order = Order::create([
            'dish_id' => $validated['dish_id'],
            'menu_id' => $todayMenu->id,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Commande réussie !',
            'order'   => $order->load('dish'),
        ], 201);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate(['status' => 'required|string']);
        $order->update($validated);
        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Commande supprimée']);
    }
}
