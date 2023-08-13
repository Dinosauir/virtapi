<?php

declare(strict_types=1);


namespace App\Modules\Company\Services;

use App\Modules\Company\Contracts\CompanyRepositoryInterface;
use App\Modules\Company\Data\CompanyUpdateData;
use App\Modules\Company\Models\Company;

abstract class AbstractCompanyUpdater
{
    public function __construct(protected readonly CompanyRepositoryInterface $companyRepository)
    {
    }

    final public function update(CompanyUpdateData $data): Company
    {
        $this->validate($data);

        $company = $this->companyRepository->getCompany($data->id);
        
        $model = $this->updateModel($company, $data);

        $model->save();

        return $model;
    }

    abstract protected function validate(CompanyUpdateData $data): void;

    abstract protected function updateModel(Company $company, CompanyUpdateData $data): Company;
}