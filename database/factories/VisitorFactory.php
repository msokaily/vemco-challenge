<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Sensor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'location_id' => Location::factory(),
            'sensor_id' => Sensor::factory(),
            'date' => fake()->date(),
            'count' => fake()->numberBetween(0, 1000),
        ];
    }
}
