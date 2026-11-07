<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class RestaurantDemoSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            'Pizza' => [
                ['Margherita Pizza', 'Tomato, mozzarella, basil', '12.50'],
                ['Mushroom Pizza', 'Mushrooms, mozzarella, herbs', '14.00'],
            ],
            'Drinks' => [
                ['Lemonade', 'Fresh lemon, lightly sweetened', '3.50'],
                ['Mineral Water', 'Still mineral water', '2.00'],
            ],
            'Desserts' => [
                ['Chocolate Cake', 'Chocolate sponge with ganache', '5.50'],
                ['Cheesecake', 'Classic baked cheesecake', '5.00'],
            ],
        ];

        foreach ($catalog as $categoryName => $items) {
            $category = Category::firstOrCreate(['name' => $categoryName]);
            foreach ($items as [$name, $description, $price]) {
                MenuItem::firstOrCreate(
                    ['category_id' => $category->id, 'name' => $name],
                    ['description' => $description, 'price' => $price, 'is_available' => true],
                );
            }
        }
    }
}
