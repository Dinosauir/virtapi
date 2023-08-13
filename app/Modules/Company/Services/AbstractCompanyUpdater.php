<?php

declare(strict_types=1);


namespace App\Modules\Company\Services;

use App\Modules\Company\Data\CompanyUpdateData;
use App\Modules\Company\Models\Company;

abstract class AbstractCompanyUpdater
{
    final public function update(Company $company, CompanyUpdateData $data): Company
    {
        $this->validate($data);

        $model = $this->updateModel($company, $data);

        $model->save();

        return $model;
    }

    abstract protected function validate(CompanyUpdateData $data): void;

    abstract protected function updateModel(Company $company, CompanyUpdateData $data): Company;
}