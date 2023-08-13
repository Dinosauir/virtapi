<?php

namespace App\Modules\Station\Controllers\Api\V1;

use App\Modules\Station\Contracts\CacheResponseServiceInterface;
use App\Modules\Station\Contracts\StationRepositoryInterface;
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
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Stations
 */
final class StationController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(
        private readonly AbstractStationCreator $stationCreator,
        private readonly AbstractStationUpdater $stationUpdater,
        private readonly AbstractStationDestroyer $stationDestroyer,
        private readonly StationRepositoryInterface $stationRepository,
        private readonly CacheResponseServiceInterface $cacheResponseService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $page = $request->input('page') ?? "1";

        return $this->cacheResponseService->getStations((string)$page);
    }

    public function searchInRadius(StationSearchRequest $request): JsonResponse
    {
        return $this->cacheResponseService->searchInRadius($request->toData());
    }

    public function store(StationStoreRequest $request): JsonResponse
    {
        $resource = StationResource::make(
            $this->stationRepository->getStation(
                $this->stationCreator->create($request->toData())->id
            )
        );

        return $resource->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @urlParam station integer required The ID of the station.
     */
    public function show(string $station): JsonResponse
    {
        return StationResource::make($this->stationRepository->getStation($station))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @urlParam station integer required The ID of the station.
     */
    public function update(StationUpdateRequest $request, string $station): JsonResponse
    {
        $model = $this->stationUpdater->update($this->stationRepository->getStation($station), $request->toData());

        return StationResource::make($model)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @urlParam station integer required The ID of the station.
     */
    public function destroy(string $station): JsonResponse
    {
        $this->stationDestroyer->destroy($this->stationRepository->getStation($station));

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
