<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
    {
        // Créer les rôles
        $admin = Role::create(['name' => 'admin']);
        $employe = Role::create(['name' => 'employe']);

        // Optionnel : Créer des permissions spécifiques
        $publishMenu = Permission::create(['name' => 'publish_menu']);
        $placeOrder = Permission::create(['name' => 'place_order']);

        // Assigner les permissions aux rôles
        $admin->givePermissionTo($publishMenu);
        $employe->givePermissionTo($placeOrder);
    }
}


