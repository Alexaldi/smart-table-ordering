<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Coffee',
                'description' => 'Menu kopi seperti espresso, americano, latte, dan cappuccino.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Non-Coffee',
                'description' => 'Minuman tanpa kopi seperti chocolate, matcha, red velvet, dan taro.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tea',
                'description' => 'Pilihan teh seperti lemon tea, lychee tea, milk tea, dan green tea.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Signature Drink',
                'description' => 'Minuman khas coffee shop dengan racikan spesial.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Snack',
                'description' => 'Camilan pendamping seperti french fries, onion ring, dan chicken wings.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pastry',
                'description' => 'Aneka pastry seperti croissant, danish, cinnamon roll, dan muffin.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dessert',
                'description' => 'Menu penutup seperti cake, pudding, waffle, dan ice cream.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rice Bowl',
                'description' => 'Menu makanan berat praktis seperti chicken rice bowl dan beef rice bowl.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
