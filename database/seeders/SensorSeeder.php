<?php

namespace Database\Seeders;

use App\Enums\SensorStatus;
use App\Models\Sensor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SensorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sensors = [
            [
                'id' => 1,
                'name' => 'Sensor 01',
                'status' => SensorStatus::ACTIVE->value,
                'location_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Camera 02',
                'status' => SensorStatus::INACTIVE->value,
                'location_id' => 2,
            ],
            [
                'id' => 3,
                'name' => 'Sensor 03',
                'status' => SensorStatus::ACTIVE->value,
                'location_id' => 1,
            ],
        ];

        foreach ($sensors as $sensor) {
            Sensor::query()->updateOrCreate(
                ['id' => $sensor['id']],
                [
                    'name' => $sensor['name'],
                    'status' => $sensor['status'],
                    'location_id' => $sensor['location_id'],
                ]
            );
        }
    }
}
