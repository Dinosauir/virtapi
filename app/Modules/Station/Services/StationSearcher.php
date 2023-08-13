<?php

declare(strict_types=1);


namespace App\Modules\Station\Services;

use App\Modules\Company\Contracts\CompanyRepositoryInterface;
use App\Modules\Station\Contracts\StationRepositoryInterface;
use App\Modules\Station\Contracts\StationSearcherInterface;
use App\Modules\Station\Data\StationSearchData;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class StationSearcher implements StationSearcherInterface
{
    public function __construct(
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly StationRepositoryInterface $stationRepository
    ) {
    }

    /**
     * @throws ValidationException
     */
    final public function getStationsInRadius(StationSearchData $data): LengthAwarePaginator
    {
        if (!$company = $this->companyRepository->getCompany($data->company_id)) {
            throw ValidationException::withMessages(['company_id' => 'Company does not exists']);
        }

        return $this->stationRepository->getStationsInRadius($company->descendents, $data);
    }
}