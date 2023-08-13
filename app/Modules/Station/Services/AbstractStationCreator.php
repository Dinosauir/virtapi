<?php

declare(strict_types=1);

namespace App\Modules\Station\Services;

use App\Modules\Station\Data\StationStoreData;
use App\Modules\Station\Models\Station;

abstract class AbstractStationCreator
{
    final public function create(StationStoreData $data): Station
    {
        $this->validate($data);

        $model = $this->createModel($data);

        $model->save();

        return $model;
    }

    abstract protected function validate(StationStoreData $data): void;

    abstract protected function createModel(StationStoreData $data): Station;
}
