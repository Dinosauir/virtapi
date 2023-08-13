<?php

declare(strict_types=1);

namespace App\Modules\Station\Services;

use App\Modules\Station\Contracts\StationRepositoryInterface;
use App\Modules\Station\Data\StationUpdateData;
use App\Modules\Station\Models\Station;

abstract class AbstractStationUpdater
{
    public function __construct(protected readonly StationRepositoryInterface $stationRepository)
    {
    }

    final public function update(StationUpdateData $data): Station
    {
        $this->validate($data);

        $station = $this->stationRepository->getStation($data->id);
        $model = $this->updateModel($station, $data);

        $model->save();

        return $model;
    }

    abstract protected function validate(StationUpdateData $data): void;

    abstract protected function updateModel(Station $station, StationUpdateData $data): Station;
}
