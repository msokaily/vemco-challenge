<?php

namespace Tests\Feature;

use App\Enums\SensorStatus;
use App\Models\Location;
use App\Models\Sensor;
use App\Models\Visitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitorApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_visitors(): void
    {
        $location = Location::factory()->create([
            'name' => 'Mall A',
        ]);

        $sensor = Sensor::factory()->create([
            'name' => 'Sensor 01',
            'status' => SensorStatus::ACTIVE->value,
            'location_id' => $location->id,
        ]);

        Visitor::factory()->create([
            'location_id' => $location->id,
            'sensor_id' => $sensor->id,
            'date' => '2025-05-11',
            'count' => 450,
        ]);

        $response = $this->getJson('/api/visitors');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.date', '2025-05-11')
            ->assertJsonPath('data.0.count', 450)
            ->assertJsonPath('data.0.location.name', 'Mall A')
            ->assertJsonPath('data.0.sensor.name', 'Sensor 01');
    }

    public function test_it_can_filter_visitors_by_date(): void
    {
        $location = Location::factory()->create();

        $sensor = Sensor::factory()->create([
            'location_id' => $location->id,
        ]);

        Visitor::factory()->create([
            'location_id' => $location->id,
            'sensor_id' => $sensor->id,
            'date' => '2025-05-10',
            'count' => 300,
        ]);

        Visitor::factory()->create([
            'location_id' => $location->id,
            'sensor_id' => $sensor->id,
            'date' => '2025-05-11',
            'count' => 450,
        ]);

        $response = $this->getJson('/api/visitors?from_date=2025-05-11');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.date', '2025-05-11')
            ->assertJsonPath('data.0.count', 450);
    }

    public function test_it_can_create_visitor_record(): void
    {
        $location = Location::factory()->create();

        $sensor = Sensor::factory()->create([
            'location_id' => $location->id,
        ]);

        $response = $this->postJson('/api/visitors', [
            'location_id' => $location->id,
            'sensor_id' => $sensor->id,
            'date' => '2025-05-11',
            'count' => 450,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.date', '2025-05-11')
            ->assertJsonPath('data.count', 450);

        $this->assertDatabaseHas('visitors', [
            'location_id' => $location->id,
            'sensor_id' => $sensor->id,
            'date' => '2025-05-11',
            'count' => 450,
        ]);
    }

    public function test_visitor_sensor_must_belong_to_selected_location(): void
    {
        $locationA = Location::factory()->create();
        $locationB = Location::factory()->create();

        $sensor = Sensor::factory()->create([
            'location_id' => $locationB->id,
        ]);

        $response = $this->postJson('/api/visitors', [
            'location_id' => $locationA->id,
            'sensor_id' => $sensor->id,
            'date' => '2025-05-11',
            'count' => 450,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['sensor_id']);
    }

    public function test_visitor_count_must_not_be_negative(): void
    {
        $location = Location::factory()->create();

        $sensor = Sensor::factory()->create([
            'location_id' => $location->id,
        ]);

        $response = $this->postJson('/api/visitors', [
            'location_id' => $location->id,
            'sensor_id' => $sensor->id,
            'date' => '2025-05-11',
            'count' => -1,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['count']);
    }
}
