<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Discount;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Coffee' => 'Menu kopi seperti espresso, americano, latte, dan cappuccino.',
            'Non-Coffee' => 'Minuman tanpa kopi seperti chocolate, matcha, red velvet, dan taro.',
            'Tea' => 'Pilihan teh seperti lemon tea, lychee tea, milk tea, dan green tea.',
            'Signature Drink' => 'Minuman khas coffee shop dengan racikan spesial.',
            'Snack' => 'Camilan pendamping seperti french fries, onion ring, dan chicken wings.',
            'Pastry' => 'Aneka pastry seperti croissant, danish, cinnamon roll, dan muffin.',
            'Dessert' => 'Menu penutup seperti cake, pudding, waffle, dan ice cream.',
            'Rice Bowl' => 'Menu makanan berat praktis seperti chicken rice bowl dan beef rice bowl.',
        ];

        foreach ($categories as $name => $description) {
            Category::updateOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }

        $menuItems = [
            ['Coffee', 'Americano', 'Double shot espresso dengan air. Hitam, kuat, dan siap kembalikan fokusmu.', 19000, 10, 18, '6f4e37'],
            ['Coffee', 'Cafe Latte', 'Espresso dengan susu creamy yang halus dan aman buat mood yang rapuh.', 24000, 10, 16, '8b5e3c'],
            ['Coffee', 'Cappuccino', 'Espresso, susu, dan foam tebal. Kopi klasik yang tidak banyak drama.', 24000, 10, 15, 'a47148'],
            ['Coffee', 'Espresso', 'Kopi pekat satu shot dengan rasa intens dan aroma bold.', 18000, 7, 20, '3f2a20'],
            ['Coffee', 'Mochaccino', 'Espresso, cokelat, dan susu creamy untuk rasa manis yang tetap serius.', 28000, 10, 12, '5a3827'],
            ['Coffee', 'Vanilla Latte', 'Latte lembut dengan wangi vanilla yang manis dan ringan.', 27000, 10, 13, 'b5855a'],
            ['Coffee', 'Hazelnut Latte', 'Latte creamy dengan aroma hazelnut hangat.', 29000, 10, 12, '9a6848'],
            ['Coffee', 'Cold Brew', 'Kopi dingin ekstraksi lama, halus, segar, dan rendah asam.', 26000, 8, 14, '4c3529'],

            ['Non-Coffee', 'Chocolate', 'Minuman cokelat manis dan creamy untuk manusia yang butuh pelukan cair.', 22000, 8, 18, '7a4a35'],
            ['Non-Coffee', 'Matcha Latte', 'Matcha dengan susu creamy, pahit tipis, estetik secukupnya.', 25000, 8, 14, '5f7f52'],
            ['Non-Coffee', 'Red Velvet Latte', 'Minuman red velvet creamy dengan rasa manis lembut.', 25000, 8, 11, '9f3f4c'],
            ['Non-Coffee', 'Taro Latte', 'Taro creamy berwarna ungu lembut dengan rasa manis ringan.', 24000, 8, 13, '7d5a9b'],
            ['Non-Coffee', 'Strawberry Milk', 'Susu segar dengan strawberry manis dan aroma buah.', 23000, 7, 12, 'c85c72'],
            ['Non-Coffee', 'Cookies and Cream', 'Minuman creamy dengan serpihan cookies renyah.', 27000, 8, 10, '505050'],

            ['Tea', 'Lemon Tea', 'Teh segar dengan lemon, cocok buat pura-pura hidup sehat.', 16000, 5, 24, 'c99a3a'],
            ['Tea', 'Lychee Tea', 'Teh manis segar dengan aroma leci.', 18000, 5, 22, 'd49c9c'],
            ['Tea', 'Peach Tea', 'Teh dingin dengan aroma peach yang fruity dan ringan.', 18000, 5, 20, 'd88f53'],
            ['Tea', 'Thai Tea', 'Teh susu khas Thailand yang manis dan creamy.', 20000, 6, 16, 'c87532'],
            ['Tea', 'Green Tea', 'Teh hijau segar dengan aftertaste ringan.', 17000, 5, 18, '6d8b4e'],
            ['Tea', 'Earl Grey Milk Tea', 'Milk tea dengan aroma bergamot yang elegan.', 21000, 6, 14, '6e5d4c'],

            ['Signature Drink', 'Kopi Susu Gula Aren', 'Kopi susu dengan gula aren. Menu penyelamat bangsa dan deadline.', 23000, 8, 20, '8d5c3f'],
            ['Signature Drink', 'Caramel Macchiato', 'Espresso, susu, dan caramel. Manis, mahal dikit, tapi ya begitulah hidup.', 28000, 10, 14, 'b97845'],
            ['Signature Drink', 'Butterscotch Coffee', 'Kopi susu dengan butterscotch yang buttery dan harum.', 30000, 10, 12, 'b9824b'],
            ['Signature Drink', 'Palm Sugar Matcha', 'Matcha creamy dengan gula aren yang wangi.', 29000, 9, 12, '748c57'],
            ['Signature Drink', 'Coconut Cold Brew', 'Cold brew segar dengan sentuhan coconut.', 31000, 8, 10, '4d756b'],
            ['Signature Drink', 'Berry Coffee Fizz', 'Kopi dingin sparkling dengan berry yang segar.', 32000, 9, 9, '8f3f5f'],

            ['Snack', 'French Fries', 'Kentang goreng renyah, teman terbaik buat ngobrol terlalu lama.', 18000, 12, 18, 'd4a037'],
            ['Snack', 'Chicken Wings', 'Sayap ayam gurih dengan saus pilihan.', 28000, 15, 12, 'a04632'],
            ['Snack', 'Onion Rings', 'Bawang bombay goreng tepung, renyah dan gurih.', 19000, 12, 14, 'c9964a'],
            ['Snack', 'Nachos Cheese', 'Nachos renyah dengan saus keju creamy.', 25000, 10, 10, 'c98432'],
            ['Snack', 'Garlic Bread', 'Roti panggang bawang putih yang harum dan buttery.', 17000, 8, 16, 'b47b43'],
            ['Snack', 'Crispy Mushroom', 'Jamur goreng renyah dengan saus cocolan.', 22000, 12, 11, '8a6842'],

            ['Pastry', 'Butter Croissant', 'Croissant buttery, flaky, dan sok Prancis.', 22000, 5, 15, 'c99754'],
            ['Pastry', 'Cinnamon Roll', 'Roti gulung kayu manis dengan glaze manis.', 24000, 5, 12, 'a86a43'],
            ['Pastry', 'Pain au Chocolat', 'Pastry flaky dengan isian cokelat.', 26000, 5, 10, '704231'],
            ['Pastry', 'Blueberry Danish', 'Danish renyah dengan topping blueberry manis.', 25000, 5, 9, '6f638f'],
            ['Pastry', 'Almond Croissant', 'Croissant almond dengan taburan kacang dan gula halus.', 28000, 5, 8, 'ba8b56'],
            ['Pastry', 'Banana Muffin', 'Muffin pisang lembut untuk teman kopi pagi.', 21000, 5, 13, 'b58b42'],

            ['Dessert', 'Chocolate Cake', 'Cake cokelat lembut untuk menenangkan hidup yang banyak error.', 26000, 5, 10, '4b2f2a'],
            ['Dessert', 'Waffle Ice Cream', 'Waffle hangat dengan ice cream manis.', 30000, 12, 9, 'c58b50'],
            ['Dessert', 'Cheesecake', 'Cheesecake creamy dengan rasa asam manis seimbang.', 32000, 5, 8, 'd1b48c'],
            ['Dessert', 'Tiramisu Cup', 'Dessert kopi creamy dengan cocoa powder.', 30000, 5, 8, '7b5741'],
            ['Dessert', 'Brownies', 'Brownies cokelat padat, legit, dan pekat.', 24000, 5, 12, '5a352f'],
            ['Dessert', 'Panna Cotta', 'Panna cotta lembut dengan saus buah.', 27000, 5, 9, 'd8bfa5'],

            ['Rice Bowl', 'Chicken Rice Bowl', 'Nasi dengan ayam berbumbu, praktis, kenyang, tidak banyak debat.', 32000, 15, 13, '9b5537'],
            ['Rice Bowl', 'Beef Rice Bowl', 'Nasi dengan beef slice gurih dan saus spesial.', 38000, 15, 10, '7c3f2d'],
            ['Rice Bowl', 'Teriyaki Chicken Bowl', 'Ayam teriyaki manis gurih dengan nasi hangat.', 34000, 15, 12, '8c4d31'],
            ['Rice Bowl', 'Spicy Tuna Bowl', 'Tuna pedas dengan nasi dan sayuran segar.', 36000, 15, 9, 'a3413c'],
            ['Rice Bowl', 'Katsu Curry Bowl', 'Chicken katsu renyah dengan saus curry hangat.', 39000, 18, 8, 'b07135'],
            ['Rice Bowl', 'Gyudon Bowl', 'Beef slice gurih manis ala gyudon.', 40000, 16, 8, '6f3d2c'],
        ];

        $createdMenuItems = [];

        foreach ($menuItems as [$categoryName, $name, $description, $price, $estimatedMinutes, $stock, $color]) {
            $category = Category::where('name', $categoryName)->first();

            if (! $category) {
                continue;
            }

            $menuItem = MenuItem::updateOrCreate(
                [
                    'category_id' => $category->id,
                    'name' => $name,
                ],
                [
                    'description' => $description,
                    'price' => $price,
                    'image_url' => $this->imageUrl($name, $color),
                    'estimated_minutes' => $estimatedMinutes,
                    'stock' => $stock,
                    'is_available' => $stock > 0,
                    'is_active' => true,
                ]
            );

            $createdMenuItems[$name] = $menuItem;
        }

        $this->seedActiveDiscounts($createdMenuItems);
    }

    private function imageUrl(string $name, string $color): string
    {
        return 'https://placehold.co/600x450/' . $color . '/ffffff?text=' . rawurlencode($name);
    }

    private function seedActiveDiscounts(array $menuItems): void
    {
        $adminId = $this->adminId();

        $discounts = [
            'Americano' => 5,
            'Cafe Latte' => 12,
            'Kopi Susu Gula Aren' => 15,
            'Caramel Macchiato' => 10,
            'French Fries' => 20,
            'Butter Croissant' => 15,
            'Waffle Ice Cream' => 18,
            'Chicken Rice Bowl' => 12,
            'Katsu Curry Bowl' => 10,
            'Matcha Latte' => 8,
        ];

        foreach ($discounts as $menuName => $percentage) {
            if (! isset($menuItems[$menuName])) {
                continue;
            }

            $discount = Discount::updateOrCreate(
                ['name' => 'Promo Seeder - ' . $menuName],
                [
                    'percentage' => $percentage,
                    'start_date' => now()->subDay(),
                    'end_date' => now()->addDays(14),
                    'created_by' => $adminId,
                ]
            );

            $menuItems[$menuName]->menuDiscounts()->firstOrCreate([
                'discount_id' => $discount->id,
            ]);
        }
    }

    private function adminId(): int
    {
        DB::table('users')->updateOrInsert(
            ['username' => 'admin'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'shift_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return (int) DB::table('users')->where('username', 'admin')->value('id');
    }
}
