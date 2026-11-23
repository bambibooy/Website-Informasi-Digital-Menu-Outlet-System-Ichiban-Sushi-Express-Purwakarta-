<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Data ini diambil langsung dari DEFAULT_MENU_DATA di script.js,
     * supaya menu yang tadinya hardcode di front-end sekarang beneran
     * ada di database dan bisa dikelola lewat admin panel.
     *
     * 'gambar' di sini masih menunjuk ke file lama di folder public/images/
     * (bukan hasil upload lewat admin) — Model Menu sudah disesuaikan supaya
     * tetap bisa menampilkan foto lama ini dengan benar.
     */
    public function run(): void
    {
        $items = [
            // ---------------- RAMEN ----------------
            ['nama' => 'Tori Teriyaki Ramen', 'kategori' => 'Ramen', 'harga' => 40700, 'gambar' => 'images/tori_teriyaki_ramen.png', 'deskripsi' => 'Ramen gurih dengan topping daging ayam bumbu teriyaki, telur, nori dan sayuran.'],
            ['nama' => 'Ebi Furai Ramen', 'kategori' => 'Ramen', 'harga' => 42900, 'gambar' => 'images/ebi_furai_ramen.png', 'deskripsi' => 'Ramen dengan topping udang goreng tepung crispy, telur, nori dan sayuran segar.'],
            ['nama' => 'Spicy Gyuniku Ramen', 'kategori' => 'Ramen', 'harga' => 46200, 'gambar' => 'images/spicy_gyuniku_ramen.png', 'deskripsi' => 'Ramen pedas dengan topping daging sapi, telur setengah matang, nori dan sayuran.'],
            ['nama' => 'Gyukara Ramen with Tori Paithan', 'kategori' => 'Ramen', 'harga' => 46200, 'gambar' => 'images/gyukara_ramen_with_paithan.png', 'deskripsi' => 'Ramen kuah kaldu ayam kental (paithan) dengan topping daging sapi pedas dan telur.'],
            ['nama' => 'Tori Abura Ramen', 'kategori' => 'Ramen', 'harga' => 40700, 'gambar' => 'images/tori_abura_ramen.png', 'deskripsi' => 'Ramen tanpa kuah (abura soba) dengan topping ayam, telur, nori dan saus spesial.'],
            ['nama' => 'Curry Tori Paithan Ramen', 'kategori' => 'Ramen', 'harga' => 46200, 'gambar' => 'images/curry_tori_paithan_ramen.png', 'deskripsi' => 'Ramen kuah kari kental khas Jepang dengan topping ayam, telur dan sayuran.'],
            ['nama' => 'Tori Katsu Ramen', 'kategori' => 'Ramen', 'harga' => 42700, 'gambar' => 'images/tori_katsu_ramen.png', 'deskripsi' => 'Ramen dengan topping ayam katsu crispy, telur, nori dan sayuran segar.'],

            // ---------------- DONBURI ----------------
            ['nama' => 'Tori Katsu Don', 'kategori' => 'Donburi', 'harga' => 37400, 'gambar' => 'images/tori_katsu_don.webp', 'deskripsi' => 'Nasi dengan topping ayam katsu crispy disiram saus donburi manis gurih.'],
            ['nama' => 'Tori Teriyaki Don', 'kategori' => 'Donburi', 'harga' => 37400, 'gambar' => 'images/tori_teriyaki_don.webp', 'deskripsi' => 'Nasi dengan topping ayam panggang saus teriyaki dan sayuran segar.'],
            ['nama' => 'Tori Katsu Curry Don', 'kategori' => 'Donburi', 'harga' => 37400, 'gambar' => 'images/tori_katsu_curry_don.webp', 'deskripsi' => 'Nasi dengan topping ayam katsu crispy disiram saus kari khas Jepang.'],
            ['nama' => 'Gyu Katsu Curry Don', 'kategori' => 'Donburi', 'harga' => 46200, 'gambar' => 'images/gyu_katsu_curry_don.webp', 'deskripsi' => 'Nasi dengan topping daging sapi katsu crispy disiram saus kari khas Jepang.'],
            ['nama' => 'Gyuniku Teriyaki Don', 'kategori' => 'Donburi', 'harga' => 46200, 'gambar' => 'images/gyuniku_teriyaki_don.webp', 'deskripsi' => 'Nasi dengan topping daging sapi panggang saus teriyaki dan sayuran segar.'],

            // ---------------- FUSHION ROLL ----------------
            ['nama' => 'Chicken Mentai Roll', 'kategori' => 'Fushion Roll', 'harga' => 30000, 'gambar' => 'images/chicken_mentai_roll.webp', 'deskripsi' => 'Nasi sushi, saus mentai creamy dan irisan cabai diatasnya.'],
            ['nama' => 'Dory Mentai Roll', 'kategori' => 'Fushion Roll', 'harga' => 30000, 'gambar' => 'images/dori_mentai_roll.webp', 'deskripsi' => 'Nasi sushi dengan dori dipadukan chili sauce dan mayo mentai.'],
            ['nama' => 'Katsu Spicy Cheese Roll', 'kategori' => 'Fushion Roll', 'harga' => 38000, 'gambar' => 'images/katsu_spicy_cheese_roll.webp', 'deskripsi' => 'Chicken katsu, keju, bubuk cabai, telur ikan dan saus keju.'],
            ['nama' => 'Dory Spicy Cheese Roll', 'kategori' => 'Fushion Roll', 'harga' => 38000, 'gambar' => 'images/dori_spicy_cheese_roll.webp', 'deskripsi' => 'Dory katsu, keju, bubuk cabai dan saus keju.'],
            ['nama' => 'Top Gun Roll', 'kategori' => 'Fushion Roll', 'harga' => 48000, 'gambar' => 'images/top_gun roll.webp', 'deskripsi' => 'Crab stick, telur ikan, tempura garing, mentimun dan mayonnes'],
            ['nama' => 'Katsu Roll', 'kategori' => 'Fushion Roll', 'harga' => 38000, 'gambar' => 'images/katsu_roll.webp', 'deskripsi' => 'Chicken katsu, telur ikan dan mayonnes pedas.'],
            ['nama' => 'Fish Cake Roll', 'kategori' => 'Fushion Roll', 'harga' => 38000, 'gambar' => 'images/fish_cake_roll.webp', 'deskripsi' => '16 Sushi; 8 salmon skin roll dengan unagi, 8 salmon skin roll dengan alpukat.'],
            ['nama' => 'Spicy Chicken Roll', 'kategori' => 'Fushion Roll', 'harga' => 38000, 'gambar' => 'images/spicy_chicken_roll.webp', 'deskripsi' => 'Ayam, cabai rawit, telur ikan dan mayonnes.'],
            ['nama' => 'California Roll', 'kategori' => 'Fushion Roll', 'harga' => 52000, 'gambar' => 'images/california_roll.webp', 'deskripsi' => 'Alpukat, crab stick, tamago, mentimun, telur ikan, wijen hitam dan mayonnes.'],

            // ---------------- BEVERAGES ----------------
            ['nama' => 'Ocha', 'kategori' => 'Beverages', 'harga' => 12100, 'gambar' => 'images/ocha.webp', 'deskripsi' => 'Minuman segar terbuat dari teh hijau.'],
            ['nama' => 'Mineral Water 330 ml', 'kategori' => 'Beverages', 'harga' => 12100, 'gambar' => 'images/mineral_water.webp', 'deskripsi' => 'Minuman air segar dalam kemasan botol'],
            ['nama' => 'Lemonade', 'kategori' => 'Beverages', 'harga' => 14300, 'gambar' => 'images/lemonade.webp', 'deskripsi' => 'Minuman segar yang terbuat dari perasan lemon.'],
            ['nama' => 'Ice Lemon Tea', 'kategori' => 'Beverages', 'harga' => 14300, 'gambar' => 'images/ice_lemon_tea.webp', 'deskripsi' => 'Minuman teh dan perasan lemon yang segar'],
            ['nama' => 'Blackcurrant Tea', 'kategori' => 'Beverages', 'harga' => 14300, 'gambar' => 'images/blackcurrant_tea.webp', 'deskripsi' => 'Minuman segar rasa anggur'],
            ['nama' => 'Orange Juice', 'kategori' => 'Beverages', 'harga' => 14300, 'gambar' => 'images/orange_juice.webp', 'deskripsi' => 'Minuman dari perasan jeruk yang segar, bercita rasa unik'],
            ['nama' => 'Tea Kotak Sosro', 'kategori' => 'Beverages', 'harga' => 14300, 'gambar' => 'images/tek_botol_sosro.webp', 'deskripsi' => 'Minuman teh dalam kemasan kotak'],
        ];

        foreach ($items as $item) {
            Menu::firstOrCreate(
                ['nama' => $item['nama'], 'kategori' => $item['kategori']], // hindari duplikat kalau seeder dijalankan 2x
                [
                    'deskripsi' => $item['deskripsi'],
                    'harga' => $item['harga'],
                    'gambar' => $item['gambar'],
                    'aktif' => true,
                ]
            );
        }
    }
}