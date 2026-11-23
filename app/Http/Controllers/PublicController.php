<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\OperatingHours;
use App\Models\OperatingHour;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    /**
     * Show the application landing page.
     */
    public function index()
    {
        // Ambil menu aktif dari database yang dikelola oleh admin
        $menus = Menu::where('aktif', true)->orderBy('id', 'asc')->get();
        $operatingHours = OperatingHour::orderBy('sort_order', 'asc')->get();

        // Ambil semua kategori carousel langsung dari database (tabel categories)
        $categories = Category::orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

        $menuData = [];
        $categoryOrder = [];

        // Kelompokkan menu berdasarkan kategori dari database
        foreach ($categories as $cat) {
            $slug = $cat->slug ?: Str::slug($cat->name);
            if (!$slug) continue;

            $categoryOrder[] = $slug;
            $catMenus = $menus->where('kategori', $cat->name);
            $items = [];

            foreach ($catMenus as $item) {
                $items[] = [
                    'id'    => $item->id,
                    'name'  => $item->nama,
                    'price' => (float) $item->harga,
                    'image' => $item->gambar_url ?: asset('images/logo_putih.png'),
                    'desc'  => $item->deskripsi ?: '',
                    'sold'  => (bool) $item->sold,
                ];
            }

            $menuData[$slug] = [
                'label'         => $cat->name,
                'categoryImage' => $cat->image_url,
                'items'         => $items,
            ];
        }

        // Tangani jika ada menu yang kategorinya belum terdaftar di tabel categories
        $registeredCatNames = $categories->pluck('name')->toArray();
        $otherCategories = $menus->pluck('kategori')->unique()->diff($registeredCatNames);

        foreach ($otherCategories as $otherCat) {
            $slug = Str::slug($otherCat);
            if (!$slug || isset($menuData[$slug])) continue;

            $catMenus = $menus->where('kategori', $otherCat);
            if ($catMenus->isNotEmpty()) {
                $categoryOrder[] = $slug;
                $items = [];

                foreach ($catMenus as $item) {
                    $items[] = [
                        'id'    => $item->id,
                        'name'  => $item->nama,
                        'price' => (float) $item->harga,
                        'image' => $item->gambar_url ?: asset('images/logo_putih.png'),
                        'desc'  => $item->deskripsi ?: '',
                        'sold'  => (bool) $item->sold,
                    ];
                }

                $menuData[$slug] = [
                    'label'         => $otherCat,
                    'categoryImage' => asset('images/Emoji.png'),
                    'items'         => $items,
                ];
            }
        }

        $hoursData = [];
        foreach ($operatingHours as $hour) {
            $hoursData[] = [
                'day'  => $hour->day,
                'time' => $hour->is_closed ? 'TUTUP' : $hour->time,
            ];
        }

        return view('publik.home', compact('menus', 'operatingHours', 'menuData', 'categoryOrder', 'hoursData'));
    }
}