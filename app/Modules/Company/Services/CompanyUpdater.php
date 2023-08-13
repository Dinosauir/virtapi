<?php

declare(strict_types=1);

namespace App\Modules\Company\Services;

use App\Modules\Company\Data\CompanyUpdateData;
use App\Modules\Company\Models\Company;
use Illuminate\Validation\ValidationException;

final class CompanyUpdater extends AbstractCompanyUpdater
{
    public const VALIDATION_MESSAGE = 'Parent company cannot be itself';

    public const VALIDATION_PARENT_MESSAGE = 'Cannot parent eachother';

    /**
     * @throws ValidationException
     */
    protected function validate(CompanyUpdateData $data): void
    {
        if ($data->parent_company_id === null) {
            return;
        }

        if ($data->parent_company_id === $data->id) {
            throw ValidationException::withMessages(['parent_company_id' => self::VALIDATION_MESSAGE]);
        }

        $company = $this->companyRepository->getCompany($data->parent_company_id);

        if ($company->parent_company_id === $data->id) {
            throw ValidationException::withMessages(['parent_company_id' => self::VALIDATION_PARENT_MESSAGE]);
        }
    }

    protected function updateModel(Company $company, CompanyUpdateData $data): Company
    {
        return $company->updateFromData($data);
    }
}
