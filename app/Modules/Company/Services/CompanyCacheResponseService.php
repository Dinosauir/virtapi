<?php

declare(strict_types=1);

namespace App\Modules\Company\Services;

use App\Modules\Company\Contracts\CompanyCacheResponseServiceInterface;
use App\Modules\Company\Contracts\CompanyRepositoryInterface;
use App\Modules\Company\Data\CompanyListData;
use App\Modules\Company\Models\Company;
use App\Modules\Company\Resources\CompanyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CompanyCacheResponseService implements CompanyCacheResponseServiceInterface
{
    public function __construct(private readonly CompanyRepositoryInterface $companyRepository)
    {
    }

    final public function getCompany(int $company_id): JsonResponse
    {
        $taggedCache = Cache::tags(Company::getCollectionCacheKey());
        $cacheKey = request()->fullUrl();

        if ($taggedCache->has($cacheKey)) {
            return $taggedCache->get($cacheKey);
        }


        $response = CompanyResource::make($this->companyRepository->getCompany($company_id))
            ->response()
            ->setStatusCode(Response::HTTP_OK);

        $taggedCache->forever($cacheKey, $response);

        return $response;
    }

    final public function getCompanies(CompanyListData $data): JsonResponse
    {
        $taggedCache = Cache::tags(Company::getCollectionCacheKey());
        $cacheKey = request()->fullUrl();

        if ($taggedCache->has($cacheKey)) {
            return $taggedCache->get($cacheKey);
        }

        $response = $this->getCollectionResponse($data->page);

        $taggedCache->forever($cacheKey, $response);

        return $response;
    }

    private function getCollectionResponse(int $page): JsonResponse
    {
        return CompanyResource::collection($this->companyRepository->getAllCompanies($page))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
