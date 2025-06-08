<?php

namespace Database\Seeders;

use App\Models\Categories;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'Admin'
        ]);
        // User::create([
        //     'name' => 'Owner',
        //     'email' => 'owner@gmail.com',
        //     'password' => Hash::make('password'),
        //     'roles' => 'Owner'
        // ]);

        // $this->call([
        //     UserSeeder::class,
        //     ProductSeeder::class,
        // ]);

        // Order::factory(200)->create();
    }
}
