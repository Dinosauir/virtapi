<?php

declare(strict_types=1);

namespace App\Modules\Company\Repositories;

use App\Modules\Company\Contracts\CompanyRepositoryInterface;
use App\Modules\Company\Models\Company;
use Illuminate\Cache\TaggedCache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

final class CompanyRepository implements CompanyRepositoryInterface
{
    public function getCompany(int $id): Company
    {
        $taggedCache = $this->getCacheTags();

        if ($taggedCache->has(Company::getCacheKey($id))) {
            return $taggedCache->get(Company::getCacheKey($id));
        }

        $company = Company::query()->with(['parent', 'children'])->findOrFail($id);
        assert($company instanceof Company);

        $taggedCache->forever(Company::getCacheKey($id), $company);

        return $company;
    }


    public function getAllCompanies(int $page): LengthAwarePaginator
    {
        $taggedCache = $this->getCacheTags();

        if ($taggedCache->has(Company::getCollectionCacheKey() . ':' . $page)) {
            return $taggedCache->get(Company::getCollectionCacheKey() . ':' . $page);
        }

        $companies = Company::query()->paginate(100);

        $taggedCache->forever(Company::getCollectionCacheKey() . ':' . $page, $companies);

        return $companies;
    }

    private function getCacheTags(): TaggedCache
    {
        return Cache::tags(Company::getCollectionCacheKey());
    }
}
