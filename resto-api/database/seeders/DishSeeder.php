<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dish;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // \App\Models\Dish::factory(10)->create();
       $plats = [
            [
                'name' => 'Pizza Margherita',
                'description' => 'Tomate, mozzarella basilic et huile d olive.',
                'price' => 4000
            ],
            [
                'name' => 'Burger Maison',
                'description' => 'Bœuf frais, cheddar, salade, tomates et frites maison.',
                'price' => 3500
            ],
            [
                'name' => 'Salade César',
                'description' => 'Poulet grillé, parmesan, croûtons et sauce César.',
                'price' => 2000
            ],
            [
                'name' => 'Tiebou yapp',
                'description' => 'Riz à la viande accompagné de sauce oignons',
                'price' => 5000
            ],
            [
                'name' => 'Tacos poulet',
                'description' => 'tortillas de poulet avec crudités et frites',
                'price' => 4500
            ],
            [
                'name' => 'Tacos viande',
                'description' => 'tortillas de viande avec crudités et frites',
                'price' => 4500
            ]
        ];

        foreach ($plats as $plat) {
            Dish::create($plat);
        }
    }
}
