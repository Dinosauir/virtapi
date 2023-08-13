<?php

declare(strict_types=1);


namespace App\Modules\Station\Services;


use App\Modules\Station\Data\StationUpdateData;
use App\Modules\Station\Models\Station;

final class StationUpdater extends AbstractStationUpdater
{
    protected function validate(StationUpdateData $data): void
    {
        return;
    }

    protected function updateModel(Station $station, StationUpdateData $data): Station
    {
        return $station->updateFromData($data);
    }
}