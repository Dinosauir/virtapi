<?php

namespace Tests\Feature;

use App\Modules\Company\Services\CompanyUpdater;
use Illuminate\Validation\ValidationException;
use Tests\Feature\Concerns\SaveCompanyTrait;
use Tests\TestCase;

final class CompanyTest extends TestCase
{
    use SaveCompanyTrait;

    public function test_get_companies(): void
    {
        $response = $this->get('/api/v1/companies');

        $response->assertStatus(200);
        $response->assertJson($response['data']);
    }

    public function test_save_company(): void
    {
        [$name, $response] = $this->saveCompany();

        $response->assertStatus(201);
        $response->assertJsonFragment(compact('name'));
    }

    public function test_save_company_with_parent(): void
    {
        [$parentName] = $this->saveCompany();

        [$name, $response] = $this->saveCompany(null, 1);

        $response->assertStatus(201);
        $response->assertJsonFragment(compact('name'));
        $response->assertJsonFragment(['name' => $parentName]);
        $response->assertJsonFragment(['descendents' => [1, 2]]);
    }

    public function test_get_companies_with_data(): void
    {
        [$name] = $this->saveCompany();

        $response = $this->get('/api/v1/companies');

        $response->assertStatus(200);
        $response->assertJsonFragment(compact('name'));
    }

    public function test_update_company(): void
    {
        $this->saveCompany();

        $name = $this->faker->name;

        $response = $this->put('/api/v1/companies/1', [
            'name' => $name,
            'parent_company_id' => null
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(compact('name'));

        $this->saveCompany();

        $name = $this->faker->name;

        $response = $this->put('/api/v1/companies/2', [
            'name' => $name,
            'parent_company_id' => 1
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(compact('name'));

        try {
            $response = $this->put('/api/v1/companies/2', [
                'name' => $name,
                'parent_company_id' => 2
            ]);
        } catch (ValidationException $exception) {
            $this->assertEquals(CompanyUpdater::VALIDATION_MESSAGE, $exception->getMessage());
        }
    }

    public function test_show_company(): void
    {
        [$name] = $this->saveCompany();

        $response = $this->get('/api/v1/companies/1');

        $response->assertJsonFragment(compact('name'));
    }

    public function test_delete_company(): void
    {
        [$name, $response] = $this->saveCompany();

        $response = $this->delete('/api/v1/companies/'.$response['data']['id']);

        $response->assertStatus(204);
    }
}
