<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MenuController extends Controller
{
    public function index()
    {
        return Menu::with('dishes')->orderBy('menu_date', 'desc')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_date'      => 'required|date|unique:menus,menu_date',
            'dishes'         => 'required|array',
            'featured_dish'  => 'nullable|integer|exists:dishes,id',
            'order_deadline' => 'nullable|date_format:H:i',
        ]);

        $menu = Menu::create([
            'menu_date'      => $request->menu_date,
            'is_published'   => true,
            'order_deadline' => $request->order_deadline ?? '11:30',
        ]);

        // Attacher les plats avec is_featured
        $attachData = [];
        foreach ($request->dishes as $dishId) {
            $attachData[$dishId] = [
                'is_featured' => ($request->featured_dish && (int)$dishId === (int)$request->featured_dish),
            ];
        }
        $menu->dishes()->attach($attachData);

        return response()->json(['message' => 'Menu publié avec succès', 'menu' => $menu->load('dishes')], 201);
    }

    /**
     * Menu du jour pour les employés – le plat featured est remonté en premier.
     */
    public function currentMenu()
    {
        $menu = Menu::with(['dishes' => function ($q) {
                $q->withPivot('is_featured')->orderByPivot('is_featured', 'desc');
            }])
            ->where('menu_date', Carbon::today())
            ->where('is_published', true)
            ->first();

        if (!$menu) {
            return response()->json(['message' => "Aucun menu pour aujourd'hui"], 404);
        }

        // Ajouter le flag de deadline dans la réponse
        $menu->deadline_passed = Carbon::now()->format('H:i') > $menu->order_deadline;

        return response()->json($menu);
    }

    public function show(Menu $menu)
    {
        return $menu->load(['dishes' => fn ($q) => $q->withPivot('is_featured')]);
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'dishes'         => 'sometimes|array',
            'featured_dish'  => 'nullable|integer|exists:dishes,id',
            'order_deadline' => 'nullable|date_format:H:i',
            'is_published'   => 'sometimes|boolean',
        ]);

        $menu->update($request->only(['order_deadline', 'is_published']));

        if ($request->has('dishes')) {
            $attachData = [];
            foreach ($request->dishes as $dishId) {
                $attachData[$dishId] = [
                    'is_featured' => ($request->featured_dish && (int)$dishId === (int)$request->featured_dish),
                ];
            }
            $menu->dishes()->sync($attachData);
        }

        return response()->json($menu->load('dishes'));
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json(['message' => 'Menu supprimé']);
    }
}
