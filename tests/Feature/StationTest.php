<?php

namespace Tests\Feature;

use Illuminate\Validation\ValidationException;
use Tests\Feature\Concerns\SaveCompanyTrait;
use Tests\TestCase;

class StationTest extends TestCase
{
    use SaveCompanyTrait;

    public function test_get_stations(): void
    {
        $response = $this->get('/api/v1/stations');

        $response->assertStatus(200);
        $response->assertJson($response['data']);
    }

    public function test_save_station(): void
    {
        [$data, $response] = $this->createStation();

        unset($data['company_id']);
        $response->assertStatus(201);
        $response->assertJsonFragment($data);
    }

    public function test_save_station_invalid_point(): void
    {
        [$name, $response] = $this->saveCompany();

        $response->assertStatus(201);
        $response->assertJsonFragment(compact('name'));

        $data = [
            'name' => $this->faker->name,
            'latitude' => 300,
            'longitude' => 200,
            'company_id' => $response['data']['id'],
            'address' => $this->faker->address
        ];

        try {
            $response = $this->post('/api/v1/stations', $data);
        } catch (ValidationException $exception) {
            $this->assertStringContainsString(
                'The latitude field must be between -90 and 90',
                $exception->getMessage()
            );
        }
    }

    public function test_get_stations_with_data(): void
    {
        [$data, $stationSaveResponse] = $this->createStation();

        $response = $this->get('/api/v1/stations');

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $stationSaveResponse['data']['name']]);
    }

    public function test_get_station(): void
    {
        [$data, $response] = $this->createStation();

        $response = $this->get('/api/v1/stations/'.$response['data']['id']);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $data['name']]);
    }

    public function test_delete_station(): void
    {
        [$data, $response] = $this->createStation();

        $response = $this->delete('/api/v1/stations/'.$response['data']['id']);

        $response->assertStatus(204);
    }

    public function test_update_station(): void
    {
        [$data, $response] = $this->createStation();

        $data = [
            'name' => $this->faker->name,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'company_id' => $data['company_id'],
            'address' => $this->faker->address
        ];

        $response = $this->put('/api/v1/stations/'.$response['data']['id'], $data);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $data['name']]);
    }

    public function test_search_in_radius(): void
    {
        [$data, $response] = $this->createStation();

        $response = $this->post('/api/v1/stations/search', [
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'company_id' => $data['company_id'],
            'radius' => 50
        ]);

        $response->assertStatus(200);

        unset($data['company_id']);
        $data['latitude'] = number_format($data['latitude'], 8);
        $data['longitude'] = number_format($data['longitude'], 8);

        $response->assertJsonFragment($data);
    }

    private function createStation(): array
    {
        [$name, $response] = $this->saveCompany();

        $response->assertStatus(201);
        $response->assertJsonFragment(compact('name'));

        $data = [
            'name' => $this->faker->name,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'company_id' => $response['data']['id'],
            'address' => $this->faker->address
        ];

        return [$data, $this->post('/api/v1/stations', $data)];
    }
}
