<?php

declare(strict_types=1);


namespace App\Modules\Company\Services;

use App\Modules\Company\Models\Company;

final class CompanyDestroyer extends AbstractCompanyDestroyer
{
    protected function validate(Company $company): void
    {
        return;
    }
}