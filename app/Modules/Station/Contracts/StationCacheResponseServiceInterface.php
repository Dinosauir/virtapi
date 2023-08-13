<?php

declare(strict_types=1);

namespace App\Modules\Station\Contracts;

use App\Modules\Station\Data\StationListData;
use App\Modules\Station\Data\StationSearchData;
use Illuminate\Http\JsonResponse;

interface StationCacheResponseServiceInterface
{
    public function getStations(StationListData $data): JsonResponse;

    public function getStation(int $station_id): JsonResponse;

    public function searchInRadius(StationSearchData $data): JsonResponse;
}
