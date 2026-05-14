<?php

namespace Database\Factories;

use App\Enums\SensorStatus;
use App\Models\Location;
use App\Models\Sensor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sensor>
 */
class SensorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Sensor ' . fake()->numberBetween(1, 99),
            'status' => fake()->randomElement([
                SensorStatus::ACTIVE->value,
                SensorStatus::INACTIVE->value,
            ]),
            'location_id' => Location::factory(),
        ];
    }
}
