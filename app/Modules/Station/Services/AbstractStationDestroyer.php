<?php

declare(strict_types=1);


namespace App\Modules\Station\Services;


use App\Modules\Station\Contracts\StationRepositoryInterface;
use App\Modules\Station\Models\Station;

abstract class AbstractStationDestroyer
{
    public function __construct(protected readonly StationRepositoryInterface $stationRepository)
    {
    }

    final public function destroy(int $station_id): ?bool
    {
        $station = $this->stationRepository->getStation($station_id);

        $this->validate($station);

        return $station->delete();
    }

    abstract protected function validate(Station $station): void;
}