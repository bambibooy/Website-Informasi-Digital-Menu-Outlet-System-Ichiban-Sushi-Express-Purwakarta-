<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ramen = Category::where('slug', 'ramen')->first();
        $donburi = Category::where('slug', 'donburi')->first();
        $roll = Category::where('slug', 'roll')->first();
        $beverages = Category::where('slug', 'beverages')->first();

        $menuData = [
            'ramen' => [
                [
                    'name' => 'Tori Teriyaki Ramen',
                    'price' => 40700,
                    'image' => 'images/tori_teriyaki_ramen.png',
                    'description' => 'Ramen gurih dengan topping daging ayam bumbu teriyaki, telur, nori dan sayuran.',
                    'is_sold' => false,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Ebi Furai Ramen',
                    'price' => 42900,
                    'image' => 'images/ebi_furai_ramen.png',
                    'description' => 'Ramen dengan topping udang goreng tepung crispy, telur, nori dan sayuran segar.',
                    'is_sold' => false,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Spicy Gyuniku Ramen',
                    'price' => 46200,
                    'image' => 'images/spicy_gyuniku_ramen.png',
                    'description' => 'Ramen pedas dengan topping daging sapi, telur setengah matang, nori dan sayuran.',
                    'is_sold' => false,
                    'sort_order' => 3,
                ],
                [
                    'name' => 'Gyukara Ramen with Tori Paithan',
                    'price' => 46200,
                    'image' => 'images/gyukara_ramen_with_paithan.png',
                    'description' => 'Ramen kuah kaldu ayam kental (paithan) dengan topping daging sapi pedas dan telur.',
                    'is_sold' => false,
                    'sort_order' => 4,
                ],
                [
                    'name' => 'Tori Abura Ramen',
                    'price' => 40700,
                    'image' => 'images/tori_abura_ramen.png',
                    'description' => 'Ramen tanpa kuah (abura soba) dengan topping ayam, telur, nori dan saus spesial.',
                    'is_sold' => false,
                    'sort_order' => 5,
                ],
                [
                    'name' => 'Curry Tori Paithan Ramen',
                    'price' => 46200,
                    'image' => 'images/curry_tori_paithan_ramen.png',
                    'description' => 'Ramen kuah kari kental khas Jepang dengan topping ayam, telur dan sayuran.',
                    'is_sold' => false,
                    'sort_order' => 6,
                ],
                [
                    'name' => 'Tori Katsu Ramen',
                    'price' => 42700,
                    'image' => 'images/tori_katsu_ramen.png',
                    'description' => 'Ramen dengan topping ayam katsu crispy, telur, nori dan sayuran segar.',
                    'is_sold' => false,
                    'sort_order' => 7,
                ],
                [
                    'name' => 'Gyuniku Teriyaki Ramen',
                    'price' => 48000,
                    'image' => 'images/gyuniku_teriyaki_ramen.webp',
                    'description' => 'Ramen istimewa dengan daging sapi panggang teriyaki premium.',
                    'is_sold' => true,
                    'sort_order' => 8,
                ],
            ],

            'donburi' => [
                [
                    'name' => 'Tori Katsu Don',
                    'price' => 37400,
                    'image' => 'images/tori_katsu_don.webp',
                    'description' => 'Nasi dengan topping ayam katsu crispy disiram saus donburi manis gurih.',
                    'is_sold' => false,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Tori Teriyaki Don',
                    'price' => 37400,
                    'image' => 'images/tori_teriyaki_don.webp',
                    'description' => 'Nasi dengan topping ayam panggang saus teriyaki dan sayuran segar.',
                    'is_sold' => false,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Tori Katsu Curry Don',
                    'price' => 37400,
                    'image' => 'images/tori_katsu_curry_don.webp',
                    'description' => 'Nasi dengan topping ayam katsu crispy disiram saus kari khas Jepang.',
                    'is_sold' => false,
                    'sort_order' => 3,
                ],
                [
                    'name' => 'Gyu Katsu Curry Don',
                    'price' => 46200,
                    'image' => 'images/gyu_katsu_curry_don.webp',
                    'description' => 'Nasi dengan topping daging sapi katsu crispy disiram saus kari khas Jepang.',
                    'is_sold' => false,
                    'sort_order' => 4,
                ],
                [
                    'name' => 'Gyuniku Teriyaki Don',
                    'price' => 46200,
                    'image' => 'images/gyuniku_teriyaki_don.webp',
                    'description' => 'Nasi dengan topping daging sapi panggang saus teriyaki dan sayuran segar.',
                    'is_sold' => false,
                    'sort_order' => 5,
                ],
            ],

            'roll' => [
                [
                    'name' => 'Chicken Mentai Roll',
                    'price' => 30000,
                    'image' => 'images/chicken_mentai_roll.webp',
                    'description' => 'Nasi sushi, saus mentai creamy dan irisan cabai diatasnya.',
                    'is_sold' => false,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Dory Mentai Roll',
                    'price' => 30000,
                    'image' => 'images/dori_mentai_roll.webp',
                    'description' => 'Nasi sushi dengan dori dipadukan chili sauce dan mayo mentai.',
                    'is_sold' => false,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Katsu Spicy Cheese Roll',
                    'price' => 38000,
                    'image' => 'images/katsu_spicy_cheese_roll.webp',
                    'description' => 'Chicken katsu, keju, bubuk cabai, telur ikan dan saus keju.',
                    'is_sold' => false,
                    'sort_order' => 3,
                ],
                [
                    'name' => 'Dory Spicy Cheese Roll',
                    'price' => 38000,
                    'image' => 'images/dori_spicy_cheese_roll.webp',
                    'description' => 'Dory katsu, keju, bubuk cabai dan saus keju.',
                    'is_sold' => false,
                    'sort_order' => 4,
                ],
                [
                    'name' => 'Top Gun Roll',
                    'price' => 48000,
                    'image' => 'images/top_gun roll.webp',
                    'description' => 'Crab stick, telur ikan, tempura garing, mentimun dan mayonnes',
                    'is_sold' => false,
                    'sort_order' => 5,
                ],
                [
                    'name' => 'Katsu Roll',
                    'price' => 38000,
                    'image' => 'images/katsu_roll.webp',
                    'description' => 'Chicken katsu, telur ikan dan mayonnes pedas.',
                    'is_sold' => false,
                    'sort_order' => 6,
                ],
                [
                    'name' => 'Fish Cake Roll',
                    'price' => 38000,
                    'image' => 'images/fish_cake_roll.webp',
                    'description' => '16 Sushi; 8 salmon skin roll dengan unagi, 8 salmon skin roll dengan alpukat.',
                    'is_sold' => false,
                    'sort_order' => 7,
                ],
                [
                    'name' => 'Spicy Chicken Roll',
                    'price' => 38000,
                    'image' => 'images/spicy_chicken_roll.webp',
                    'description' => 'Ayam, cabai rawit, telur ikan dan mayonnes.',
                    'is_sold' => false,
                    'sort_order' => 8,
                ],
                [
                    'name' => 'California Roll',
                    'price' => 52000,
                    'image' => 'images/california_roll.webp',
                    'description' => 'Alpukat, crab stick, tamago, mentimun, telur ikan, wijen hitam dan mayonnes.',
                    'is_sold' => false,
                    'sort_order' => 9,
                ],
            ],

            'beverages' => [
                [
                    'name' => 'Ocha',
                    'price' => 12100,
                    'image' => 'images/ocha.webp',
                    'description' => 'Minuman segar terbuat dari teh hijau.',
                    'is_sold' => false,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Mineral Water 330 ml',
                    'price' => 12100,
                    'image' => 'images/mineral_water.webp',
                    'description' => 'Minuman air segar dalam kemasan botol',
                    'is_sold' => false,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Lemonade',
                    'price' => 14300,
                    'image' => 'images/lemonade.webp',
                    'description' => 'Minuman segar yang terbuat dari perasan lemon.',
                    'is_sold' => false,
                    'sort_order' => 3,
                ],
                [
                    'name' => 'Ice Lemon Tea',
                    'price' => 14300,
                    'image' => 'images/ice_lemon_tea.webp',
                    'description' => 'Minuman teh dan perasan lemon yang segar',
                    'is_sold' => false,
                    'sort_order' => 4,
                ],
                [
                    'name' => 'Blackcurrant Tea',
                    'price' => 14300,
                    'image' => 'images/blackcurrant_tea.webp',
                    'description' => 'Minuman segar rasa anggur',
                    'is_sold' => false,
                    'sort_order' => 5,
                ],
                [
                    'name' => 'Orange Juice',
                    'price' => 14300,
                    'image' => 'images/orange_juice.webp',
                    'description' => 'Minuman dari perasan jeruk yang segar, bercita rasa unik',
                    'is_sold' => false,
                    'sort_order' => 6,
                ],
                [
                    'name' => 'Tea Kotak Sosro',
                    'price' => 14300,
                    'image' => 'images/tek_botol_sosro.webp',
                    'description' => 'Minuman teh dalam kemasan kotak',
                    'is_sold' => false,
                    'sort_order' => 7,
                ],
            ],
        ];

        $categoriesMap = [
            'ramen' => $ramen,
            'donburi' => $donburi,
            'roll' => $roll,
            'beverages' => $beverages,
        ];

        foreach ($menuData as $catSlug => $items) {
            $cat = $categoriesMap[$catSlug] ?? null;
            if (!$cat) continue;

            foreach ($items as $item) {
                MenuItem::updateOrCreate(
                    ['category_id' => $cat->id, 'name' => $item['name']],
                    $item
                );
            }
        }
    }
}
