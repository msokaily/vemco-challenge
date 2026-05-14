<?php

namespace Database\Seeders;

use App\Models\Visitor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $visitors = [
            [
                'id' => 1,
                'location_id' => 1,
                'sensor_id' => 1,
                'date' => '2025-05-10',
                'count' => 300,
            ],
            [
                'id' => 2,
                'location_id' => 2,
                'sensor_id' => 2,
                'date' => '2025-05-10',
                'count' => 200,
            ],
            [
                'id' => 3,
                'location_id' => 1,
                'sensor_id' => 1,
                'date' => '2025-05-11',
                'count' => 450,
            ],
        ];

        foreach ($visitors as $visitor) {
            Visitor::query()->updateOrCreate(
                ['id' => $visitor['id']],
                [
                    'location_id' => $visitor['location_id'],
                    'sensor_id' => $visitor['sensor_id'],
                    'date' => $visitor['date'],
                    'count' => $visitor['count'],
                ]
            );
        }
    }
}
