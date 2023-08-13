<?php

declare(strict_types=1);


namespace App\Modules\Station\Services;

use App\Modules\Station\Contracts\StationCacheResponseServiceInterface;
use App\Modules\Station\Contracts\StationRepositoryInterface;
use App\Modules\Station\Contracts\StationSearcherInterface;
use App\Modules\Station\Data\StationListData;
use App\Modules\Station\Data\StationSearchData;
use App\Modules\Station\Models\Station;
use App\Modules\Station\Repositories\StationRepository;
use App\Modules\Station\Resources\StationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

final class StationStationCacheResponseService implements StationCacheResponseServiceInterface
{
    public function __construct(
        private readonly StationRepositoryInterface $stationRepository,
        private readonly StationSearcherInterface $stationSearcher
    ) {
    }

    public function getStations(StationListData $data): JsonResponse
    {
        $taggedCache = Cache::tags(Station::getCollectionCacheKey());
        $cacheKey = request()->fullUrl();

        if ($taggedCache->has($cacheKey)) {
            return $taggedCache->get($cacheKey);
        }

        $response = $this->getCollectionResponse($data->page);

        $taggedCache->forever($cacheKey, $response);

        return $response;
    }

    public function getStation(int $station_id): JsonResponse
    {
        $taggedCache = Cache::tags(Station::getCollectionCacheKey());
        $cacheKey = request()->fullUrl();

        if ($taggedCache->has($cacheKey)) {
            return $taggedCache->get($cacheKey);
        }
        $response = StationResource::make($this->stationRepository->getStation($station_id))
            ->response()
            ->setStatusCode(Response::HTTP_OK);

        $taggedCache->forever($cacheKey, $response);

        return $response;
    }

    public function searchInRadius(StationSearchData $data): JsonResponse
    {
        $taggedCache = Cache::tags(Station::getCollectionCacheKey());
        $cacheKey = StationRepository::getSearchCacheKey($data);

        if ($taggedCache->has($cacheKey)) {
            return $taggedCache->get($cacheKey);
        }

        $response = $this->getSearchResponse($data);

        $taggedCache->forever($cacheKey, $response);

        return $response;
    }

    private function getCollectionResponse(int $page): JsonResponse
    {
        return StationResource::collection($this->stationRepository->getAllStations($page))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    private function getSearchResponse(StationSearchData $data): JsonResponse
    {
        return StationResource::collection($this->stationSearcher->getStationsInRadius($data))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}