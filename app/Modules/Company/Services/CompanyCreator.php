<?php

declare(strict_types=1);

namespace App\Modules\Company\Services;

use App\Modules\Company\Data\CompanyStoreData;
use App\Modules\Company\Models\Company;

final class CompanyCreator extends AbstractCompanyCreator
{
    protected function validate(CompanyStoreData $data): void
    {
        return;
    }

    protected function createModel(CompanyStoreData $data): Company
    {
        return Company::createFromData($data);
    }
}
