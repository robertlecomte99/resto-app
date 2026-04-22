<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $request->validate([
            'menu_date' => 'required|date|unique:menus,menu_date',
            'dishes' => 'required|array', // Liste des IDs des plats 
        ]);

        // Créer le menu
        $menu = Menu::create([
            'menu_date' => $request->menu_date,
            'is_published' => true
        ]);

        // Attacher les plats dans la table pivot menu_dishes 
        $menu->dishes()->attach($request->dishes);

        return response()->json(['message' => 'Menu publié avec succès', 'menu' => $menu], 201);
    }

    // 2. Récupérer le menu du jour pour l'employé 
    public function currentMenu()
    {
        $menu = Menu::with('dishes')
            ->where('menu_date', Carbon::today())
            ->where('is_published', true)
            ->first();

        if (!$menu) {
            return response()->json(['message' => 'Aucun menu pour aujourd’hui'], 404);
        }

        return response()->json($menu);
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        //
    }
}
