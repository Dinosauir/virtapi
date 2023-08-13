<?php

declare(strict_types=1);


namespace App\Modules\Station\Services;


use App\Modules\Station\Data\StationUpdateData;
use App\Modules\Station\Models\Station;

abstract class AbstractStationUpdater
{
    final public function update(Station $station, StationUpdateData $data): Station
    {
        $this->validate($data);

        $model = $this->updateModel($station, $data);

        $model->save();

        return $model;
    }

    abstract protected function validate(StationUpdateData $data): void;

    abstract protected function updateModel(Station $station, StationUpdateData $data): Station;
}