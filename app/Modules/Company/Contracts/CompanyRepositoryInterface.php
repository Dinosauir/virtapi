<?php

declare(strict_types=1);

namespace App\Modules\Company\Contracts;

use App\Modules\Company\Models\Company;
use Illuminate\Pagination\LengthAwarePaginator;

interface CompanyRepositoryInterface
{
    public function getCompany(int $id): Company;

    public function getAllCompanies(int $page): LengthAwarePaginator;
}
