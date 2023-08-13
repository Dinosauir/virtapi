<?php

namespace App\Modules\Station\Observers;

use App\Modules\Station\Models\Station;
use Illuminate\Support\Facades\Cache;

final class StationObserver
{
    public function created(Station $station): void
    {
        Cache::tags(Station::getCollectionCacheKey())->flush();

        Cache::tags(Station::getCollectionCacheKey())->forever(Station::getCacheKey($station->id), $station);
    }

    public function updated(Station $station): void
    {
        Cache::tags(Station::getCollectionCacheKey())->flush();

        Cache::tags(Station::getCollectionCacheKey())->forever(Station::getCacheKey($station->id), $station);
    }

    public function deleted(Station $station): void
    {
        Cache::tags(Station::getCollectionCacheKey())->flush();
    }

    public function forceDeleted(Station $station): void
    {
        Cache::tags(Station::getCollectionCacheKey())->flush();
    }
}
