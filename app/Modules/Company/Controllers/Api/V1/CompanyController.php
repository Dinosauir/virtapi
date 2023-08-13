<?php

namespace App\Modules\Company\Controllers\Api\V1;

use App\Modules\Company\Contracts\CompanyRepositoryInterface;
use App\Modules\Company\Requests\CompanyStoreRequestAbstract;
use App\Modules\Company\Requests\CompanyUpdateRequest;
use App\Modules\Company\Resources\CompanyResource;
use App\Modules\Company\Services\AbstractCompanyCreator;
use App\Modules\Company\Services\AbstractCompanyDestroyer;
use App\Modules\Company\Services\AbstractCompanyUpdater;
use App\Modules\Company\Services\CacheResponseService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly CacheResponseService $cacheResponseService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $page = $request->input('page') ?? "1";

        return $this->cacheResponseService->getCompanies((string)$page);
    }

    public function store(CompanyStoreRequestAbstract $request): JsonResponse
    {
        $resource = CompanyResource::make($this->companyCreator->create($request->toData()));

        return $resource->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @urlParam company integer required The ID of the company.
     */
    public function show(string $company): JsonResponse
    {
        return CompanyResource::make($this->companyRepository->getCompany($company))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @urlParam company integer required The ID of the company.
     */
    public function update(CompanyUpdateRequest $request, string $company): JsonResponse
    {
        $model = $this->companyUpdater->update($this->companyRepository->getCompany($company), $request->toData());

        return CompanyResource::make($model)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @urlParam company integer required The ID of the company.
     */
    public function destroy(string $company): JsonResponse
    {
        $this->companyDestroyer->destroy($this->companyRepository->getCompany($company));

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
