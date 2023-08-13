<?php

namespace App\Modules\Company\Providers;

use App\Modules\Company\Contracts\CompanyCacheResponseServiceInterface;
use App\Modules\Company\Contracts\CompanyRepositoryInterface;
use App\Modules\Company\Controllers\Api\V1\CompanyController;
use App\Modules\Company\Models\Company;
use App\Modules\Company\Observers\CompanyObserver;
use App\Modules\Company\Repositories\CompanyRepository;
use App\Modules\Company\Services\AbstractCompanyCreator;
use App\Modules\Company\Services\AbstractCompanyDestroyer;
use App\Modules\Company\Services\AbstractCompanyUpdater;
use App\Modules\Company\Services\CompanyCacheResponseService;
use App\Modules\Company\Services\CompanyCreator;
use App\Modules\Company\Services\CompanyDestroyer;
use App\Modules\Company\Services\CompanyUpdater;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

final class CompanyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(CompanyController::class)
            ->needs(AbstractCompanyCreator::class)
            ->give(static function (Application $app) {
                return $app->make(CompanyCreator::class);
            });

        $this->app->when(CompanyController::class)
            ->needs(AbstractCompanyUpdater::class)
            ->give(static function (Application $app) {
                return $app->make(CompanyUpdater::class);
            });

        $this->app->when(CompanyController::class)
            ->needs(AbstractCompanyDestroyer::class)
            ->give(static function (Application $app) {
                return $app->make(CompanyDestroyer::class);
            });

        $this->app->when(CompanyController::class)
            ->needs(CompanyCacheResponseServiceInterface::class)
            ->give(static function (Application $app) {
                return $app->make(CompanyCacheResponseService::class);
            });

        // CONTROLLERS

        $this->app->when(CompanyUpdater::class)
            ->needs(CompanyRepositoryInterface::class)
            ->give(static function (Application $app) {
                return $app->make(CompanyRepository::class);
            });

        $this->app->when(CompanyDestroyer::class)
            ->needs(CompanyRepositoryInterface::class)
            ->give(static function (Application $app) {
                return $app->make(CompanyRepository::class);
            });

        $this->app->when(CompanyCacheResponseService::class)
            ->needs(CompanyRepositoryInterface::class)
            ->give(static function (Application $app) {
                return $app->make(CompanyRepository::class);
            });

        if ($this->app->runningUnitTests()) {
            $this->app->bind(AbstractCompanyCreator::class, CompanyCreator::class);
            $this->app->bind(AbstractCompanyUpdater::class, CompanyUpdater::class);
            $this->app->bind(AbstractCompanyDestroyer::class, CompanyDestroyer::class);
            $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        }
    }

    public function boot(): void
    {
        Company::observe(CompanyObserver::class);

        if (file_exists($this->getRoutePath())) {
            $this->loadRoutesFrom($this->getRoutePath());
        }
    }


    private function getRouteBasePath(): string
    {
        return str_replace('Providers', 'routes', __DIR__);
    }

    private function getRoutePath(string $filename = 'api.php'): string
    {
        return $this->getRouteBasePath().'/'.$filename;
    }
}
