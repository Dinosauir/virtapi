<?php

declare(strict_types=1);

namespace App\Modules\Company\Contracts;

use App\Modules\Company\Data\CompanyListData;
use Illuminate\Http\JsonResponse;

interface CompanyCacheResponseServiceInterface
{
    public function getCompanies(CompanyListData $data): JsonResponse;

    public function getCompany(int $company_id): JsonResponse;
}
