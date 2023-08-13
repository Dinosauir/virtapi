<?php

declare(strict_types=1);


namespace App\Modules\Station\Contracts;

use App\Modules\Station\Data\StationSearchData;
use App\Modules\Station\Models\Station;
use Illuminate\Pagination\LengthAwarePaginator;

interface StationRepositoryInterface
{
    public function getStation(int $id): Station;

    public function getStationsInRadius(array $descendents, StationSearchData $data): LengthAwarePaginator;

    public function getAllStations(string $page = "1"): LengthAwarePaginator;
}