<?php

declare(strict_types=1);


namespace App\Modules\Station\Repositories;

use App\Modules\Station\Contracts\StationRepositoryInterface;
use App\Modules\Station\Data\StationSearchData;
use App\Modules\Station\Models\Station;
use Illuminate\Cache\TaggedCache;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

final class StationRepository implements StationRepositoryInterface
{
    public function getStation(int $id): Station
    {
        $taggedCache = $this->getCacheTags();

        if ($taggedCache->has(Station::getCacheKey($id))) {
            return $taggedCache->get(Station::getCacheKey($id));
        }

        $station = Station::query()->with(['company'])->findOrFail($id);
        assert($station instanceof Station);

        $taggedCache->forever(Station::getCacheKey($id), $station);

        return $station;
    }

    public function getAllStations(string $page = "1"): LengthAwarePaginator
    {
        $taggedCache = $this->getCacheTags();

        if ($taggedCache->has(Station::getCollectionCacheKey().':'.$page)) {
            return $taggedCache->get(Station::getCollectionCacheKey().':'.$page);
        }

        $stations = Station::query()->with(['company'])->paginate(100);

        $taggedCache->forever(Station::getCollectionCacheKey().':'.$page, $stations);

        return $stations;
    }

    public function getStationsInRadius(array $descendents, StationSearchData $data): LengthAwarePaginator
    {
        $cacheKey = self::getSearchCacheKey($data);

        $taggedCache = $this->getCacheTags();

        if ($taggedCache->has($cacheKey)) {
            return $taggedCache->get($cacheKey);
        }

        $stations = Station::query()
            ->whereIn('company_id', $descendents)
            ->selectRaw(
                "
                    id,
                    name,
                    latitude,
                    longitude,
                    company_id,
                    address,
                    ST_Distance_Sphere(location, POINT({$data->longitude->value()}, {$data->latitude->value()}))
                    AS distance
                    "
            )
            ->whereRaw(
                "
                     ST_Distance_Sphere(location, POINT({$data->longitude->value()}, {$data->latitude->value()}))
                     < 
                     {$data->radius} * 1000
                     "
            )
            ->orderBy('distance')
            ->paginate(100);

        $stations = $this->getGroupedPaginator($stations);

        $taggedCache->forever($cacheKey, $stations);

        return $stations;
    }

    private function getGroupedPaginator(LengthAwarePaginator $stations): LengthAwarePaginator
    {
        $groupedStations = [];

        foreach ($stations as $station) {
            $key = $station->latitude.':'.$station->longitude;
            if (!isset($groupedStations[$key])) {
                $groupedStations[$key] = [];
            }

            $groupedStations[$key][] = $station;
        }

        return new LengthAwarePaginator(
            $groupedStations,
            $stations->total(),
            $stations->perPage(),
            $stations->currentPage(),
            ['path' => $stations->path(), 'pageName' => 'page']
        );
    }

    private function getCacheTags(): TaggedCache
    {
        return Cache::tags([Station::getCollectionCacheKey()]);
    }

    public static function getSearchCacheKey(StationSearchData $data): string
    {
        return $data->latitude.':'.$data->longitude.':'.$data->radius.':'.$data->page.':'.$data->company_id;
    }
}