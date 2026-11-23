<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories (JSON API).
     */
    public function index(Request $request)
    {
        $categories = Category::withCount('menus')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($categories);
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $slug = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);

        if (!$slug) {
            $slug = 'kategori-' . time();
        }

        // Ensure unique slug
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        $nextSort = (Category::max('sort_order') ?? 0) + 1;

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'image' => $imagePath,
            'sort_order' => isset($validated['sort_order']) && $validated['sort_order'] !== '' ? (int) $validated['sort_order'] : $nextSort,
        ]);

        $category->loadCount('menus');

        return response()->json($category, 201);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'hapus_image' => 'nullable',
        ]);

        $oldName = $category->name;
        $newName = $validated['name'];

        $slug = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);

        if (!$slug) {
            $slug = $category->slug ?: 'kategori-' . $category->id;
        }

        if ($slug !== $category->slug) {
            $originalSlug = $slug;
            $counter = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $category->slug = $slug;
        }

        $category->name = $newName;
        if (isset($validated['sort_order']) && $validated['sort_order'] !== '') {
            $category->sort_order = (int) $validated['sort_order'];
        }

        if ($request->hasFile('image')) {
            if ($category->image && !str_starts_with($category->image, 'images/')) {
                Storage::disk('public')->delete($category->image);
            }
            $category->image = $request->file('image')->store('categories', 'public');
        } elseif ($request->boolean('hapus_image')) {
            if ($category->image && !str_starts_with($category->image, 'images/')) {
                Storage::disk('public')->delete($category->image);
            }
            $category->image = null;
        }

        $category->save();

        // Update menu yang menggunakan kategori lama jika nama kategori diubah
        if ($oldName !== $newName) {
            Menu::where('kategori', $oldName)->update(['kategori' => $newName]);
        }

        $category->loadCount('menus');

        return response()->json($category);
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->image && !str_starts_with($category->image, 'images/')) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return response()->json(null, 204);
    }
}
