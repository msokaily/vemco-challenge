<?php

namespace Tests\Feature;

use App\Enums\SensorStatus;
use App\Models\Location;
use App\Models\Sensor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SensorApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_it_can_list_sensors(): void
    {
        $location = Location::factory()->create([
            'name' => 'Mall A',
        ]);

        Sensor::factory()->create([
            'name' => 'Sensor 01',
            'status' => SensorStatus::ACTIVE->value,
            'location_id' => $location->id,
        ]);

        $response = $this->getJson('/api/sensors');

        $response->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonFragment([
                'name' => 'Sensor 01',
                'status' => 'active',
            ])
            ->assertJsonPath('data.data.0.location.name', 'Mall A');
    }

    public function test_it_can_filter_sensors_by_status(): void
    {
        $location = Location::factory()->create();

        Sensor::factory()->create([
            'name' => 'Active Sensor',
            'status' => SensorStatus::ACTIVE->value,
            'location_id' => $location->id,
        ]);

        Sensor::factory()->create([
            'name' => 'Inactive Sensor',
            'status' => SensorStatus::INACTIVE->value,
            'location_id' => $location->id,
        ]);

        $response = $this->getJson('/api/sensors?status=active');

        $response->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonFragment([
                'name' => 'Active Sensor',
                'status' => 'active',
            ]);
    }

    public function test_it_can_create_sensor(): void
    {
        $location = Location::factory()->create();

        $response = $this->postJson('/api/sensors', [
            'name' => 'Sensor 04',
            'status' => SensorStatus::ACTIVE->value,
            'location_id' => $location->id,
        ]);

        $response->assertCreated()
            ->assertJsonPath('message', 'Sensor created successfully')
            ->assertJsonPath('data.name', 'Sensor 04')
            ->assertJsonPath('data.status', 'active');

        $this->assertDatabaseHas('sensors', [
            'name' => 'Sensor 04',
            'status' => SensorStatus::ACTIVE->value,
            'location_id' => $location->id,
        ]);
    }

    public function test_sensor_status_must_be_valid(): void
    {
        $location = Location::factory()->create();

        $response = $this->postJson('/api/sensors', [
            'name' => 'Sensor 04',
            'status' => 'broken',
            'location_id' => $location->id,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);
    }

    public function test_sensor_location_must_exist(): void
    {
        $response = $this->postJson('/api/sensors', [
            'name' => 'Sensor 04',
            'status' => SensorStatus::ACTIVE->value,
            'location_id' => 999,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['location_id']);
    }
}
