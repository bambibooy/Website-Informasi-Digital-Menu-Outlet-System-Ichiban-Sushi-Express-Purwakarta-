<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'kategori',
        'harga',
        'aktif',
        'sold',
        'gambar',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'sold' => 'boolean',
        'harga' => 'integer',
    ];

    // supaya 'gambar_url' otomatis ikut ke JSON tiap kali Menu di-return (dipakai admin.js: item.gambar_url)
    protected $appends = ['gambar_url'];

    public function getGambarUrlAttribute(): ?string
    {
        if (!$this->gambar) {
            return null;
        }

        // Foto lama hasil seed dari script.js, sudah ada duluan di folder public/images/...
        if (str_starts_with($this->gambar, 'images/')) {
            return asset($this->gambar);
        }

        // Foto hasil upload lewat form admin, disimpan di storage/app/public/menus/...
        return Storage::disk('public')->url($this->gambar);
    }
}