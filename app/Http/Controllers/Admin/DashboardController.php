<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\OperatingHour;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $categoryCount = Category::count();
        $menuItemCount = MenuItem::count();
        $soldOutCount = MenuItem::where('is_sold', true)->count();
        $availableCount = MenuItem::where('is_sold', false)->count();

        $categories = Category::withCount('menuItems')->orderBy('sort_order', 'asc')->get();
        $recentMenuItems = MenuItem::with('category')->latest()->take(6)->get();
        $operatingHours = OperatingHour::orderBy('sort_order', 'asc')->get();

        return view('admin.dashboard', compact(
            'categoryCount',
            'menuItemCount',
            'soldOutCount',
            'availableCount',
            'categories',
            'recentMenuItems',
            'operatingHours'
        ));
    }
}
