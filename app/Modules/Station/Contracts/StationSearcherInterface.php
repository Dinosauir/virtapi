<?php

declare(strict_types=1);

namespace App\Modules\Station\Contracts;

use App\Modules\Station\Data\StationSearchData;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface StationSearcherInterface
{
    public function getStationsInRadius(StationSearchData $data): LengthAwarePaginator;
}
