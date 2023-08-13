<?php

declare(strict_types=1);


namespace App\Modules\Station\Services;


use App\Modules\Station\Models\Station;

final class StationDestroyer extends AbstractStationDestroyer
{
    protected function validate(Station $station): void
    {
        return;
    }
}