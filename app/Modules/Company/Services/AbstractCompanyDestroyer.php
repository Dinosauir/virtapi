<?php

declare(strict_types=1);

namespace App\Modules\Company\Services;

use App\Modules\Company\Contracts\CompanyRepositoryInterface;
use App\Modules\Company\Models\Company;

abstract class AbstractCompanyDestroyer
{
    public function __construct(protected readonly CompanyRepositoryInterface $companyRepository)
    {
    }

    final public function destroy(int $company_id): ?bool
    {
        $company = $this->companyRepository->getCompany($company_id);

        $this->validate($company);

        return $company->delete();
    }

    abstract protected function validate(Company $company): void;
}
