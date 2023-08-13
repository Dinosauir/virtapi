<?php

namespace Database\Factories\Modules\Station\Models;

use App\Modules\Company\Models\Company;
use App\Modules\Station\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class StationFactory extends Factory
{
    protected $model = Station::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $latitude = $this->faker->latitude;
        $longitude = $this->faker->longitude;

        return [
            'name' => $this->faker->name,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'location' => DB::raw("POINT({$longitude}, {$latitude})"),
            'address' => $this->faker->address,
            'company_id' => Company::query()->find(1)?->id ?? Company::factory()
        ];
    }
}
