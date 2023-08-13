<?php

declare(strict_types=1);


namespace App\Modules\Company\Services;

use App\Modules\Company\Data\CompanyUpdateData;
use App\Modules\Company\Models\Company;
use Illuminate\Validation\ValidationException;

final class CompanyUpdater extends AbstractCompanyUpdater
{
    /**
     * @throws ValidationException
     */
    protected function validate(CompanyUpdateData $data): void
    {
        if ($data->parent_company_id === $data->id) {
            throw ValidationException::withMessages(['parent_company_id' => 'Parent company cannot be itself']);
        }

        return;
    }

    protected function updateModel(Company $company, CompanyUpdateData $data): Company
    {
        return $company->updateFromData($data);
    }
}