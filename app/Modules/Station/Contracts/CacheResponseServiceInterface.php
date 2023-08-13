<?php

declare(strict_types=1);


namespace App\Modules\Station\Contracts;

use App\Modules\Station\Data\StationSearchData;
use Illuminate\Http\JsonResponse;

interface CacheResponseServiceInterface
{
    public function getStations(string $page = "1"): JsonResponse;

    public function searchInRadius(StationSearchData $data): JsonResponse;
}