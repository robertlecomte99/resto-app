<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'Junior',
            'email' => 'junior@resto.com',
            'password' => bcrypt('password'), // Ne jamais stocker en clair !
            
        ])->assignRole('employe');
    }
}
