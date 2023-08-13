<?php

namespace Tests\Unit;

use App\Modules\Company\Contracts\CompanyRepositoryInterface;
use App\Modules\Company\Data\CompanyStoreData;
use App\Modules\Company\Data\CompanyUpdateData;
use App\Modules\Company\Models\Company;
use App\Modules\Company\Services\AbstractCompanyCreator;
use App\Modules\Company\Services\AbstractCompanyDestroyer;
use App\Modules\Company\Services\AbstractCompanyUpdater;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

final class CompanyTest extends TestCase
{
    private CompanyRepositoryInterface $companyRepository;

    private AbstractCompanyCreator $companyCreator;

    private AbstractCompanyUpdater $companyUpdater;

    private AbstractCompanyDestroyer $companyDestroyer;

    public function setUp(): void
    {
        parent::setUp();

        $this->companyRepository = $this->app->make(CompanyRepositoryInterface::class);
        $this->companyDestroyer = $this->app->make(AbstractCompanyDestroyer::class);
        $this->companyCreator = $this->app->make(AbstractCompanyCreator::class);
        $this->companyUpdater = $this->app->make(AbstractCompanyUpdater::class);
    }

    public function test_create_company(): void
    {
        $name = $this->faker->company;

        $data = $this->createCompanyData($name);

        $this->createCompany($name, $data);
    }

    public function test_create_company_with_parent(): void
    {
        $name = $this->faker->company;

        $data = $this->createCompanyData($name);

        $company = $this->createCompany($name, $data);

        $company = $this->companyCreator->create(
            new CompanyStoreData(
                name: $name,
                parent_company_id: $company->id
            )
        );

        $this->assertEquals(2, $company->id);
        $this->assertEquals(1, $company->parent_company_id);
    }

    public function test_update_company(): void
    {
        $name = $this->faker->company;

        $data = $this->createCompanyData($name);
        $company = $this->createCompany($name, $data);

        $newName = $this->faker->company;

        $data = new CompanyUpdateData(
            id: $company->id,
            name: $newName,
            parent_company_id: null
        );

        $this->assertEquals($newName, $data->name);
        $this->assertEquals(1, $data->id);
        $this->assertEquals(null, $data->parent_company_id);
        $this->assertEquals(['id' => $company->id, 'name' => $newName, 'parent_company_id' => null], $data->toArray());

        $company = $this->companyUpdater->update($data);

        $this->assertEquals(1, $company->id);
        $this->assertEquals(null, $company->parent_company_id);
        $this->assertEquals($newName, $company->name);
    }

    public function test_remove_company(): void
    {
        $name = $this->faker->company;

        $data = $this->createCompanyData($name);
        $company = $this->createCompany($name, $data);

        $response = $this->companyDestroyer->destroy($company->id);

        $this->assertTrue($response);
    }

    public function test_remove_company_with_parent(): void
    {
        $name = $this->faker->company;
        $data = $this->createCompanyData($name);
        $company = $this->createCompany($name, $data);

        $company2 = $this->companyCreator->create(
            new CompanyStoreData(
                name: $this->faker->company,
                parent_company_id: $company->id
            )
        );


        $response = $this->companyDestroyer->destroy($company->id);
        $this->assertTrue($response);

        $response = $this->companyDestroyer->destroy($company2->id);
        $this->assertTrue($response);
    }

    public function test_get_company(): void
    {
        $name = $this->faker->company;
        $data = $this->createCompanyData($name);
        $company = $this->createCompany($name, $data);

        Cache::tags(Company::getCollectionCacheKey())->flush();
        $company = $this->companyRepository->getCompany($company->id);

        $this->assertEquals(1, $company->id);
        $this->assertEquals($name, $company->name);
    }

    public function test_get_companies(): void
    {
        $name = $this->faker->company;
        $data = $this->createCompanyData($name);
        $company = $this->createCompany($name, $data);

        $paginator = $this->companyRepository->getAllCompanies(1);

        $this->assertCount(1, $paginator);
    }

    private function createCompanyData(string $name): CompanyStoreData
    {
        $data = new CompanyStoreData(
            name: $name,
            parent_company_id: null
        );

        $this->assertEquals($name, $data->name);
        $this->assertEquals(null, $data->parent_company_id);
        $this->assertEquals(['name' => $name, 'parent_company_id' => null], $data->toArray());
        return $data;
    }

    private function createCompany(string $name, CompanyStoreData $data): Company
    {
        $company = $this->companyCreator->create($data);

        $this->assertEquals(1, $company->id);
        $this->assertEquals($name, $company->name);

        return $company;
    }
}
