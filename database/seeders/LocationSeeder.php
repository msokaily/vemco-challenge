<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['id' => 1, 'name' => 'Mall A'],
            ['id' => 2, 'name' => 'Mall B'],
        ];

        foreach ($locations as $location) {
            Location::query()->updateOrCreate(
                ['id' => $location['id']],
                ['name' => $location['name']]
            );
        }
    }
}
