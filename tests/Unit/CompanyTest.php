<?php

namespace Tests\Unit;

use App\Modules\Company\Contracts\CompanyRepositoryInterface;
use App\Modules\Company\Data\CompanyStoreData;
use App\Modules\Company\Data\CompanyUpdateData;
use App\Modules\Company\Services\AbstractCompanyCreator;
use App\Modules\Company\Services\AbstractCompanyDestroyer;
use App\Modules\Company\Services\AbstractCompanyUpdater;
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

        $company = $this->companyCreator->create(
            new CompanyStoreData(
                name: $name,
                parent_company_id: null
            )
        );

        $this->assertEquals(1, $company->id);
        $this->assertEquals($name, $company->name);
    }


    public function test_create_company_with_parent(): void
    {
        $name = $this->faker->company;

        $company = $this->companyCreator->create(
            new CompanyStoreData(
                name: $name,
                parent_company_id: null
            )
        );

        $this->assertEquals(1, $company->id);
        $this->assertEquals($name, $company->name);


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

        $company = $this->companyCreator->create(
            new CompanyStoreData(
                name: $name,
                parent_company_id: null
            )
        );

        $this->assertEquals(1, $company->id);
        $this->assertEquals($name, $company->name);


        $newName = $this->faker->company;

        $company = $this->companyUpdater->update(
            $company,
            new CompanyUpdateData(
                id: $company->id,
                name: $newName,
                parent_company_id: null
            )
        );

        $this->assertEquals(1, $company->id);
        $this->assertEquals(null, $company->parent_company_id);
        $this->assertEquals($newName, $company->name);
    }

    public function test_remove_company(): void
    {
        $name = $this->faker->company;

        $company = $this->companyCreator->create(
            new CompanyStoreData(
                name: $name,
                parent_company_id: null
            )
        );

        $response = $this->companyDestroyer->destroy($company);

        $this->assertTrue($response);
    }

    public function test_remove_company_with_parent(): void
    {
        $name = $this->faker->company;

        $company = $this->companyCreator->create(
            new CompanyStoreData(
                name: $name,
                parent_company_id: null
            )
        );

        $company2 = $this->companyCreator->create(
            new CompanyStoreData(
                name: $this->faker->company,
                parent_company_id: 1
            )
        );


        $response = $this->companyDestroyer->destroy($company);
        $this->assertTrue($response);

        $response = $this->companyDestroyer->destroy($company2);
        $this->assertTrue($response);
    }

    public function test_get_company(): void
    {
        $name = $this->faker->company;

        $company = $this->companyCreator->create(
            new CompanyStoreData(
                name: $name,
                parent_company_id: null
            )
        );

        $company = $this->companyRepository->getCompany($company->id);

        $this->assertEquals(1, $company->id);
        $this->assertEquals($name, $company->name);
    }

    public function test_get_companies(): void
    {
        $name = $this->faker->company;

        $company = $this->companyCreator->create(
            new CompanyStoreData(
                name: $name,
                parent_company_id: null
            )
        );

        $paginator = $this->companyRepository->getAllCompanies();

        $this->assertCount(1, $paginator);
    }
}
