<?php

declare(strict_types=1);


namespace App\Modules\Station\Services;

use App\Modules\Station\Contracts\CacheResponseServiceInterface;
use App\Modules\Station\Contracts\StationRepositoryInterface;
use App\Modules\Station\Contracts\StationSearcherInterface;
use App\Modules\Station\Data\StationSearchData;
use App\Modules\Station\Models\Station;
use App\Modules\Station\Repositories\StationRepository;
use App\Modules\Station\Resources\StationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheResponseService implements CacheResponseServiceInterface
{
    public function __construct(
        private readonly StationRepositoryInterface $stationRepository,
        private readonly StationSearcherInterface $stationSearcher
    ) {
    }

    final public function getStations(string $page = "1"): JsonResponse
    {
        $taggedCache = Cache::tags(Station::getCollectionCacheKey());
        $cacheKey = request()->fullUrl();

        if ($taggedCache->has($cacheKey)) {
            return $taggedCache->get($cacheKey);
        }

        $response = $this->getCollectionResponse($page);

        $taggedCache->forever($cacheKey, $response);

        return $response;
    }


    final public function searchInRadius(StationSearchData $data): JsonResponse
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

    private function getCollectionResponse(string $page = "1"): JsonResponse
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