<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the menu items.
     */
    public function index(Request $request)
    {
        $query = MenuItem::with('category');

        // Category Filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Status Filter
        if ($request->filled('status')) {
            if ($request->status === 'sold') {
                $query->where('is_sold', true);
            } elseif ($request->status === 'available') {
                $query->where('is_sold', false);
            }
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $menuItems = $query->orderBy('category_id', 'asc')
                           ->orderBy('sort_order', 'asc')
                           ->paginate(15)
                           ->withQueryString();

        $categories = Category::orderBy('name', 'asc')->get();

        return view('admin.menu-items.index', compact('menuItems', 'categories'));
    }

    /**
     * Show the form for creating a new menu item.
     */
    public function create(Request $request)
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $selectedCategoryId = $request->get('category_id');
        $nextSortOrder = (MenuItem::where('category_id', $selectedCategoryId)->max('sort_order') ?? 0) + 1;

        return view('admin.menu-items.create', compact('categories', 'selectedCategoryId', 'nextSortOrder'));
    }

    /**
     * Store a newly created menu item in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'description' => 'nullable|string|max:1000',
            'is_sold' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu-items', 'public');
        }

        MenuItem::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'image' => $imagePath,
            'description' => $validated['description'] ?? null,
            'is_sold' => $request->boolean('is_sold'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.menu-items.index', ['category_id' => $validated['category_id']])
            ->with('success', 'Menu berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified menu item.
     */
    public function edit(MenuItem $menuItem)
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.menu-items.edit', compact('menuItem', 'categories'));
    }

    /**
     * Update the specified menu item in storage.
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'description' => 'nullable|string|max:1000',
            'is_sold' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $menuItem->category_id = $validated['category_id'];
        $menuItem->name = $validated['name'];
        $menuItem->price = $validated['price'];
        $menuItem->description = $validated['description'] ?? null;
        $menuItem->is_sold = $request->boolean('is_sold');
        $menuItem->sort_order = $validated['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            if ($menuItem->image && !str_starts_with($menuItem->image, 'images/')) {
                Storage::disk('public')->delete($menuItem->image);
            }
            $menuItem->image = $request->file('image')->store('menu-items', 'public');
        }

        $menuItem->save();

        return redirect()->route('admin.menu-items.index', ['category_id' => $menuItem->category_id])
            ->with('success', 'Menu berhasil diperbarui!');
    }

    /**
     * Toggle the sold status of a menu item.
     */
    public function toggleSold(MenuItem $menuItem)
    {
        $menuItem->is_sold = !$menuItem->is_sold;
        $menuItem->save();

        $statusText = $menuItem->is_sold ? 'ditandai SOLD (Habis)' : 'ditandai TERSEDIA';

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_sold' => $menuItem->is_sold,
                'message' => "Menu {$menuItem->name} {$statusText}.",
            ]);
        }

        return back()->with('success', "Status menu {$menuItem->name} {$statusText}.");
    }

    /**
     * Remove the specified menu item from storage.
     */
    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->image && !str_starts_with($menuItem->image, 'images/')) {
            Storage::disk('public')->delete($menuItem->image);
        }

        $menuItem->delete();

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu berhasil dihapus.');
    }
}
