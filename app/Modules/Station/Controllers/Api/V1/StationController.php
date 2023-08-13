<?php

namespace App\Modules\Station\Controllers\Api\V1;

use App\Modules\Station\Contracts\StationCacheResponseServiceInterface;
use App\Modules\Station\Requests\StationListRequest;
use App\Modules\Station\Requests\StationSearchRequest;
use App\Modules\Station\Requests\StationStoreRequest;
use App\Modules\Station\Requests\StationUpdateRequest;
use App\Modules\Station\Resources\StationResource;
use App\Modules\Station\Services\AbstractStationCreator;
use App\Modules\Station\Services\AbstractStationDestroyer;
use App\Modules\Station\Services\AbstractStationUpdater;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Stations
 */
final class StationController extends Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;

    public function __construct(
        private readonly AbstractStationCreator $stationCreator,
        private readonly AbstractStationUpdater $stationUpdater,
        private readonly AbstractStationDestroyer $stationDestroyer,
        private readonly StationCacheResponseServiceInterface $cacheResponseService
    ) {
    }

    public function index(StationListRequest $request): JsonResponse
    {
        return $this->cacheResponseService->getStations($request->toData());
    }

    public function searchInRadius(StationSearchRequest $request): JsonResponse
    {
        return $this->cacheResponseService->searchInRadius($request->toData());
    }

    public function store(StationStoreRequest $request): JsonResponse
    {
        $resource = StationResource::make($this->stationCreator->create($request->toData()));

        return $resource->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @urlParam station integer required The ID of the station.
     */
    public function show(int $station): JsonResponse
    {
        return $this->cacheResponseService->getStation($station);
    }

    /**
     * @urlParam station integer required The ID of the station.
     */
    public function update(StationUpdateRequest $request, int $station): JsonResponse
    {
        return StationResource::make($this->stationUpdater->update($request->toData()))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @urlParam station integer required The ID of the station.
     */
    public function destroy(int $station): JsonResponse
    {
        $this->stationDestroyer->destroy($station);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
