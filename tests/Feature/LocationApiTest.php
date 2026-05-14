<?php

namespace Tests\Feature;

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_locations(): void
    {
        Location::factory()->create([
            'name' => 'Mall A',
        ]);

        Location::factory()->create([
            'name' => 'Mall B',
        ]);

        $response = $this->getJson('/api/locations');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment([
                'name' => 'Mall A',
            ])
            ->assertJsonFragment([
                'name' => 'Mall B',
            ]);
    }

    public function test_it_can_create_location(): void
    {
        $response = $this->postJson('/api/locations', [
            'name' => 'Mall A',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Mall A');

        $this->assertDatabaseHas('locations', [
            'name' => 'Mall A',
        ]);
    }

    public function test_location_name_is_required(): void
    {
        $response = $this->postJson('/api/locations', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }
}
