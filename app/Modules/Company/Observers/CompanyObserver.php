<?php

namespace App\Modules\Company\Observers;

use App\Modules\Company\Models\Company;
use Illuminate\Support\Facades\Cache;

final class CompanyObserver
{
    public function created(Company $company): void
    {
        Cache::tags(Company::getCollectionCacheKey())->flush();

        Cache::tags(Company::getCollectionCacheKey())->forever(Company::getCacheKey($company->id), $company);
    }

    public function updated(Company $company): void
    {
        Cache::tags(Company::getCollectionCacheKey())->flush();

        Cache::tags(Company::getCollectionCacheKey())->forever(Company::getCacheKey($company->id), $company);
    }

    public function deleted(Company $company): void
    {
        Cache::tags(Company::getCollectionCacheKey())->forever(Company::getCacheKey($company->id), $company);
    }

    public function forceDeleted(Company $company): void
    {
        Cache::tags(Company::getCollectionCacheKey())->forever(Company::getCacheKey($company->id), $company);
    }
}
