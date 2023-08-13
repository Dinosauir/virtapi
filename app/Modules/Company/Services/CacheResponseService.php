<?php

declare(strict_types=1);


namespace App\Modules\Company\Services;

use App\Modules\Company\Contracts\CacheResponseServiceInterface;
use App\Modules\Company\Contracts\CompanyRepositoryInterface;
use App\Modules\Company\Models\Company;
use App\Modules\Company\Resources\CompanyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheResponseService implements CacheResponseServiceInterface
{
    public function __construct(private readonly CompanyRepositoryInterface $companyRepository)
    {
    }

    final public function getCompanies(string $page = "1"): JsonResponse
    {
        $taggedCache = Cache::tags(Company::getCollectionCacheKey());
        $cacheKey = request()->fullUrl();

        if ($taggedCache->has($cacheKey)) {
            return $taggedCache->get($cacheKey);
        }

        $response = $this->getCollectionResponse($page);

        $taggedCache->forever($cacheKey, $response);

        return $response;
    }

    private function getCollectionResponse(string $page = "1"): JsonResponse
    {
        return CompanyResource::collection($this->companyRepository->getAllCompanies($page))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}