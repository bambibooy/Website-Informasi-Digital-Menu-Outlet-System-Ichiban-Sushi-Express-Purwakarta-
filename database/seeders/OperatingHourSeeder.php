<?php

namespace Database\Seeders;

use App\Models\OperatingHour;
use Illuminate\Database\Seeder;

class OperatingHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hours = [
            ['day' => 'SENIN', 'time' => '10:00 - 21:45', 'is_closed' => false, 'sort_order' => 1],
            ['day' => 'SELASA', 'time' => '10:00 - 21:45', 'is_closed' => false, 'sort_order' => 2],
            ['day' => 'RABU', 'time' => '10:00 - 21:45', 'is_closed' => false, 'sort_order' => 3],
            ['day' => 'KAMIS', 'time' => '10:00 - 21:45', 'is_closed' => false, 'sort_order' => 4],
            ['day' => 'JUMAT', 'time' => '10:00 - 21:45', 'is_closed' => false, 'sort_order' => 5],
            ['day' => 'SABTU', 'time' => '10:00 - 21:45', 'is_closed' => false, 'sort_order' => 6],
            ['day' => 'MINGGU', 'time' => '10:00 - 21:45', 'is_closed' => false, 'sort_order' => 7],
        ];

        foreach ($hours as $item) {
            OperatingHour::updateOrCreate(
                ['day' => $item['day']],
                $item
            );
        }
    }
}
