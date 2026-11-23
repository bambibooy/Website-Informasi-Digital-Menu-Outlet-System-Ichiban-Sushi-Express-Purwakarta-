<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    private const KATEGORI_LIST = ['Ramen', 'Donburi', 'Fushion Roll', 'Beverages'];

    /**
     * GET /api/menus
     * Dipakai admin.js: loadAppData() -> fetchMenus()
     * Butuh login (auth), menampilkan SEMUA menu (aktif maupun tidak).
     */
    public function index()
    {
        return Menu::orderByDesc('id')->get();
    }

    /**
     * GET /api/menus/public
     * Dipakai halaman publik (script.js), tanpa login.
     * Hanya menampilkan menu yang aktif = true.
     */
    public function publicIndex()
    {
        return Menu::where('aktif', true)->orderByDesc('id')->get();
    }

    /**
     * POST /api/menus
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'      => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'kategori'  => ['required', 'string', 'max:100'],
            'harga'     => ['required', 'numeric', 'min:0'],
            'aktif'     => ['nullable'],
            'sold'      => ['nullable'],
            'gambar'    => ['nullable', 'image', 'max:2048'], // max 2MB, jpg/jpeg/png/webp/dll
        ]);

        $menu = new Menu();
        $menu->nama = $data['nama'];
        $menu->deskripsi = $data['deskripsi'] ?? null;
        $menu->kategori = $data['kategori'];
        $menu->harga = (int) $data['harga'];
        $menu->aktif = $request->boolean('aktif');
        $menu->sold = $request->boolean('sold');

        if ($request->hasFile('gambar')) {
            $menu->gambar = $request->file('gambar')->store('menus', 'public');
        }

        $menu->save();

        return response()->json($menu, 201);
    }

    /**
     * PUT /api/menus/{menu}
     * Dikirim via FormData + _method=PUT (method spoofing), karena bisa membawa file.
     */
    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'nama'          => ['required', 'string', 'max:255'],
            'deskripsi'     => ['nullable', 'string'],
            'kategori'      => ['required', 'string', 'max:100'],
            'harga'         => ['required', 'numeric', 'min:0'],
            'aktif'         => ['nullable'],
            'sold'          => ['nullable'],
            'gambar'        => ['nullable', 'image', 'max:2048'],
            'hapus_gambar'  => ['nullable'],
        ]);

        $menu->nama = $data['nama'];
        $menu->deskripsi = $data['deskripsi'] ?? null;
        $menu->kategori = $data['kategori'];
        $menu->harga = (int) $data['harga'];
        $menu->aktif = $request->boolean('aktif');
        $menu->sold = $request->boolean('sold');

        if ($request->hasFile('gambar')) {
            // hapus foto lama kalau ada di storage (bukan file statis di public/images/)
            if ($menu->gambar && !str_starts_with($menu->gambar, 'images/')) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $menu->gambar = $request->file('gambar')->store('menus', 'public');
        } elseif ($request->boolean('hapus_gambar')) {
            if ($menu->gambar && !str_starts_with($menu->gambar, 'images/')) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $menu->gambar = null;
        }

        $menu->save();

        return response()->json($menu);
    }

    /**
     * PATCH /api/menus/{menu}/toggle
     * Body JSON: { "aktif": true|false }
     */
    public function toggleStatus(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'aktif' => ['required', 'boolean'],
        ]);

        $menu->aktif = $data['aktif'];
        $menu->save();

        return response()->json($menu);
    }

    /**
     * DELETE /api/menus/{menu}
     */
    public function destroy(Menu $menu)
    {
        if ($menu->gambar && !str_starts_with($menu->gambar, 'images/')) {
            Storage::disk('public')->delete($menu->gambar);
        }
        $menu->delete();

        return response()->json(null, 204);
    }
}