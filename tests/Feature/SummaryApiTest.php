<?php

namespace Tests\Feature;

use App\Enums\SensorStatus;
use App\Models\Location;
use App\Models\Sensor;
use App\Models\Visitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SummaryApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_it_returns_summary_data(): void
    {
        Carbon::setTestNow('2025-05-11');

        $location = Location::factory()->create();

        $activeSensor = Sensor::factory()->create([
            'status' => SensorStatus::ACTIVE->value,
            'location_id' => $location->id,
        ]);

        Sensor::factory()->create([
            'status' => SensorStatus::INACTIVE->value,
            'location_id' => $location->id,
        ]);

        Visitor::factory()->create([
            'location_id' => $location->id,
            'sensor_id' => $activeSensor->id,
            'date' => '2025-05-10',
            'count' => 300,
        ]);

        Visitor::factory()->create([
            'location_id' => $location->id,
            'sensor_id' => $activeSensor->id,
            'date' => '2025-05-11',
            'count' => 450,
        ]);

        Visitor::factory()->create([
            'location_id' => $location->id,
            'sensor_id' => $activeSensor->id,
            'date' => '2025-04-20',
            'count' => 999,
        ]);

        $response = $this->getJson('/api/summary');

        $response->assertOk()
            ->assertJsonPath('data.period.from', '2025-05-05')
            ->assertJsonPath('data.period.to', '2025-05-11')
            ->assertJsonPath('data.total_visitors_past_7_days', 750)
            ->assertJsonPath('data.sensors.active', 1)
            ->assertJsonPath('data.sensors.inactive', 1);

        Carbon::setTestNow();
    }
}
