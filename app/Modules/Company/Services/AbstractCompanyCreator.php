<?php

declare(strict_types=1);

namespace App\Modules\Company\Services;

use App\Modules\Company\Data\CompanyStoreData;
use App\Modules\Company\Models\Company;

abstract class AbstractCompanyCreator
{
    final public function create(CompanyStoreData $data): Company
    {
        $this->validate($data);

        $model = $this->createModel($data);

        $model->save();

        return $model;
    }

    abstract protected function validate(CompanyStoreData $data): void;

    abstract protected function createModel(CompanyStoreData $data): Company;
}
