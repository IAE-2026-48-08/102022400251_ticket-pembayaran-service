<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $statuses = ['LUNAS', 'PENDING', 'AUDITED'];
        for ($i = 0; $i < 50; $i++) {
            $row = chr(65 + ($i % 5)); // A, B, C, D, E
            $number = ($i % 10) + 1; // 1 to 10
            \App\Models\Ticket::create([
                'schedule_id' => 'SCH-' . (1000 + $i),
                'seat_number' => $row . $number,
                'total_price' => 50000 + (($i * 3500) % 150000),
                'status' => $statuses[$i % count($statuses)],
                'receipt_number' => ($i % 3 === 2) ? 'REC-' . (20260000 + $i) : null,
            ]);
        }
    }
}
