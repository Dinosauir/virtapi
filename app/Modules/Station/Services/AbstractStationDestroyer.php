<?php

declare(strict_types=1);


namespace App\Modules\Station\Services;


use App\Modules\Station\Models\Station;

abstract class AbstractStationDestroyer
{
    final public function destroy(Station $station): ?bool
    {
        $this->validate($station);

        return $station->delete();
    }

    abstract protected function validate(Station $station): void;
}