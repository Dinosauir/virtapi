<?php

declare(strict_types=1);


namespace App\Modules\Company\Services;

use App\Modules\Company\Models\Company;

abstract class AbstractCompanyDestroyer
{
    final public function destroy(Company $company): ?bool
    {
        $this->validate($company);

        return $company->delete();
    }

    abstract protected function validate(Company $company): void;
}