<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $menuItems = [
            [
                'category' => 'Coffee',
                'name' => 'Americano',
                'description' => 'Double shot espresso dengan air. Hitam, kuat, dan siap kembalikan fokusmu.',
                'price' => 19000,
                'estimated_minutes' => 10,
                'stock' => 10,
            ],
            [
                'category' => 'Coffee',
                'name' => 'Cafe Latte',
                'description' => 'Espresso dengan susu creamy yang halus dan aman buat mood yang rapuh.',
                'price' => 24000,
                'estimated_minutes' => 10,
                'stock' => 12,
            ],
            [
                'category' => 'Coffee',
                'name' => 'Cappuccino',
                'description' => 'Espresso, susu, dan foam tebal. Kopi klasik yang tidak banyak drama.',
                'price' => 24000,
                'estimated_minutes' => 10,
                'stock' => 12,
            ],
            [
                'category' => 'Non-Coffee',
                'name' => 'Chocolate',
                'description' => 'Minuman cokelat manis dan creamy untuk manusia yang butuh pelukan cair.',
                'price' => 22000,
                'estimated_minutes' => 8,
                'stock' => 15,
            ],
            [
                'category' => 'Non-Coffee',
                'name' => 'Matcha Latte',
                'description' => 'Matcha dengan susu creamy, pahit tipis, estetik secukupnya.',
                'price' => 25000,
                'estimated_minutes' => 8,
                'stock' => 10,
            ],
            [
                'category' => 'Tea',
                'name' => 'Lemon Tea',
                'description' => 'Teh segar dengan lemon, cocok buat pura-pura hidup sehat.',
                'price' => 16000,
                'estimated_minutes' => 5,
                'stock' => 20,
            ],
            [
                'category' => 'Tea',
                'name' => 'Lychee Tea',
                'description' => 'Teh manis segar dengan aroma leci.',
                'price' => 18000,
                'estimated_minutes' => 5,
                'stock' => 20,
            ],
            [
                'category' => 'Signature Drink',
                'name' => 'Kopi Susu Gula Aren',
                'description' => 'Kopi susu dengan gula aren. Menu penyelamat bangsa dan deadline.',
                'price' => 23000,
                'estimated_minutes' => 8,
                'stock' => 15,
            ],
            [
                'category' => 'Signature Drink',
                'name' => 'Caramel Macchiato',
                'description' => 'Espresso, susu, dan caramel. Manis, mahal dikit, tapi ya begitulah hidup.',
                'price' => 28000,
                'estimated_minutes' => 10,
                'stock' => 10,
            ],
            [
                'category' => 'Snack',
                'name' => 'French Fries',
                'description' => 'Kentang goreng renyah, teman terbaik buat ngobrol terlalu lama.',
                'price' => 18000,
                'estimated_minutes' => 12,
                'stock' => 15,
            ],
            [
                'category' => 'Snack',
                'name' => 'Chicken Wings',
                'description' => 'Sayap ayam gurih dengan saus pilihan.',
                'price' => 28000,
                'estimated_minutes' => 15,
                'stock' => 10,
            ],
            [
                'category' => 'Pastry',
                'name' => 'Butter Croissant',
                'description' => 'Croissant buttery, flaky, dan sok Prancis.',
                'price' => 22000,
                'estimated_minutes' => 5,
                'stock' => 10,
            ],
            [
                'category' => 'Pastry',
                'name' => 'Cinnamon Roll',
                'description' => 'Roti gulung kayu manis dengan glaze manis.',
                'price' => 24000,
                'estimated_minutes' => 5,
                'stock' => 8,
            ],
            [
                'category' => 'Dessert',
                'name' => 'Chocolate Cake',
                'description' => 'Cake cokelat lembut untuk menenangkan hidup yang banyak error.',
                'price' => 26000,
                'estimated_minutes' => 5,
                'stock' => 8,
            ],
            [
                'category' => 'Dessert',
                'name' => 'Waffle Ice Cream',
                'description' => 'Waffle hangat dengan ice cream manis.',
                'price' => 30000,
                'estimated_minutes' => 12,
                'stock' => 7,
            ],
            [
                'category' => 'Rice Bowl',
                'name' => 'Chicken Rice Bowl',
                'description' => 'Nasi dengan ayam berbumbu, praktis, kenyang, tidak banyak debat.',
                'price' => 32000,
                'estimated_minutes' => 15,
                'stock' => 10,
            ],
            [
                'category' => 'Rice Bowl',
                'name' => 'Beef Rice Bowl',
                'description' => 'Nasi dengan beef slice gurih dan saus spesial.',
                'price' => 38000,
                'estimated_minutes' => 15,
                'stock' => 8,
            ],
        ];

        foreach ($menuItems as $item) {
            $category = Category::where('name', $item['category'])->first();

            if (! $category) {
                continue;
            }

            MenuItem::updateOrCreate(
                [
                    'category_id' => $category->id,
                    'name' => $item['name'],
                ],
                [
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'image_url' => null,
                    'estimated_minutes' => $item['estimated_minutes'],
                    'stock' => $item['stock'],
                    'is_available' => $item['stock'] > 0,
                    'is_active' => true,
                ]
            );
        }
    }
}
