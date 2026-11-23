<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'slug' => 'ramen',
                'name' => 'Ramen',
                'image' => 'images/Ramen_kategori.png',
                'sort_order' => 1,
            ],
            [
                'slug' => 'donburi',
                'name' => 'Donburi',
                'image' => 'images/Donburi_kategori.png',
                'sort_order' => 2,
            ],
            [
                'slug' => 'roll',
                'name' => 'Fushion Roll',
                'image' => 'images/Fushion_Roll_kategori.png',
                'sort_order' => 3,
            ],
            [
                'slug' => 'beverages',
                'name' => 'Beverages',
                'image' => 'images/Beverages_kategori.png',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
