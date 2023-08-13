<?php

namespace App\Modules\Company\Controllers\Api\V1;

use App\Modules\Company\Contracts\CompanyCacheResponseServiceInterface;
use App\Modules\Company\Requests\CompanyListRequest;
use App\Modules\Company\Requests\CompanyStoreRequestAbstract;
use App\Modules\Company\Requests\CompanyUpdateRequest;
use App\Modules\Company\Resources\CompanyResource;
use App\Modules\Company\Services\AbstractCompanyCreator;
use App\Modules\Company\Services\AbstractCompanyDestroyer;
use App\Modules\Company\Services\AbstractCompanyUpdater;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Companies
 */
final class CompanyController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(
        private readonly AbstractCompanyCreator $companyCreator,
        private readonly AbstractCompanyUpdater $companyUpdater,
        private readonly AbstractCompanyDestroyer $companyDestroyer,
        private readonly CompanyCacheResponseServiceInterface $cacheResponseService
    ) {
    }

    public function index(CompanyListRequest $request): JsonResponse
    {
        return $this->cacheResponseService->getCompanies($request->toData());
    }

    public function store(CompanyStoreRequestAbstract $request): JsonResponse
    {
        $resource = CompanyResource::make($this->companyCreator->create($request->toData()));

        return $resource->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @urlParam company integer required The ID of the company.
     */
    public function show(int $company): JsonResponse
    {
        return $this->cacheResponseService->getCompany($company);
    }

    /**
     * @urlParam company integer required The ID of the company.
     */
    public function update(CompanyUpdateRequest $request, int $company): JsonResponse
    {
        return CompanyResource::make($this->companyUpdater->update($request->toData()))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @urlParam company integer required The ID of the company.
     */
    public function destroy(int $company): JsonResponse
    {
        $this->companyDestroyer->destroy($company);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
