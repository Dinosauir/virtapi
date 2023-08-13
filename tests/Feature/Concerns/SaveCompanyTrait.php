<?php

declare(strict_types=1);


namespace Tests\Feature\Concerns;

trait SaveCompanyTrait
{
    private function saveCompany(string|null $name = null, int|null $parent_id = null): array
    {
        if (!$name) {
            $name = $this->faker->company;
        }

        return [
            $name,
            $this->post('/api/v1/companies', [
                'name' => $name,
                'parent_company_id' => $parent_id
            ])
        ];
    }
}