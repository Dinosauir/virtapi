<?php

declare(strict_types=1);


namespace App\Modules\Station\Services;


use App\Modules\Station\Data\StationStoreData;
use App\Modules\Station\Models\Station;

final class StationCreator extends AbstractStationCreator
{
    protected function validate(StationStoreData $data): void
    {
        return;
    }

    protected function createModel(StationStoreData $data): Station
    {
        return Station::createFromData($data);
    }
}