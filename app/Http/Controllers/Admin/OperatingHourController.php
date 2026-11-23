<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OperatingHour;
use Illuminate\Http\Request;

class OperatingHourController extends Controller
{
    /**
     * Display operating hours
     */
    public function index(Request $request)
    {
        $operatingHours = OperatingHour::orderBy('sort_order', 'asc')->get();

        if ($request->wantsJson()) {
            return response()->json($operatingHours);
        }

        return view('admin.operating-hours.index', compact('operatingHours'));
    }

    /**
     * Update all operating hours in bulk
     */
    public function update(Request $request)
    {
        $request->validate([
            'hours' => 'required|array',
            'hours.*.id' => 'nullable|integer',
            'hours.*.day' => 'required|string|max:50',
            'hours.*.sort_order' => 'nullable|integer',
            'hours.*.time' => 'required|string|max:50',
            'hours.*.is_closed' => 'nullable|boolean',
        ]);

        foreach ($request->hours as $item) {
            // updateOrCreate berdasarkan nama hari: kalau baris untuk hari itu
            // belum ada di tabel (mis. tabel masih kosong), otomatis dibuat.
            OperatingHour::updateOrCreate(
                ['day' => $item['day']],
                [
                    'time' => $item['time'],
                    'is_closed' => isset($item['is_closed']) && $item['is_closed'] == 1,
                    'sort_order' => $item['sort_order'] ?? 0,
                ]
            );
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Jam operasional berhasil diperbarui!',
                'data' => OperatingHour::orderBy('sort_order', 'asc')->get(),
            ]);
        }

        return redirect()->route('admin.operating-hours.index')->with('success', 'Jam operasional berhasil diperbarui!');
    }
}