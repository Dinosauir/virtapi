<?php

namespace Database\Factories\Modules\Company\Models;

use App\Modules\Company\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'parent_company_id' => $this->faker->randomDigit() === 2 ? Company::factory()->make() : null
        ];
    }
}
