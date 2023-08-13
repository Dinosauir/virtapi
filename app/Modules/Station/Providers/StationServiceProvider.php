<?php

namespace App\Modules\Station\Providers;

use App\Modules\Company\Contracts\CompanyRepositoryInterface;
use App\Modules\Company\Repositories\CompanyRepository;
use App\Modules\Station\Contracts\StationCacheResponseServiceInterface;
use App\Modules\Station\Contracts\StationRepositoryInterface;
use App\Modules\Station\Contracts\StationSearcherInterface;
use App\Modules\Station\Controllers\Api\V1\StationController;
use App\Modules\Station\Models\Station;
use App\Modules\Station\Observers\StationObserver;
use App\Modules\Station\Repositories\StationRepository;
use App\Modules\Station\Services\AbstractStationCreator;
use App\Modules\Station\Services\AbstractStationDestroyer;
use App\Modules\Station\Services\AbstractStationUpdater;
use App\Modules\Station\Services\StationStationCacheResponseService;
use App\Modules\Station\Services\StationCreator;
use App\Modules\Station\Services\StationDestroyer;
use App\Modules\Station\Services\StationSearcher;
use App\Modules\Station\Services\StationUpdater;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

final class StationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(StationController::class)
            ->needs(AbstractStationCreator::class)
            ->give(static function (Application $app) {
                return $app->make(StationCreator::class);
            });

        $this->app->when(StationController::class)
            ->needs(AbstractStationUpdater::class)
            ->give(static function (Application $app) {
                return $app->make(StationUpdater::class);
            });

        $this->app->when(StationController::class)
            ->needs(AbstractStationDestroyer::class)
            ->give(static function (Application $app) {
                return $app->make(StationDestroyer::class);
            });

        $this->app->when(StationController::class)
            ->needs(StationSearcherInterface::class)
            ->give(static function (Application $app) {
                return $app->make(StationSearcher::class);
            });

        $this->app->when(StationController::class)
            ->needs(StationCacheResponseServiceInterface::class)
            ->give(static function (Application $app) {
                return $app->make(StationStationCacheResponseService::class);
            });

        //        CONTROLLERS

        $this->app->when(StationDestroyer::class)
            ->needs(StationRepositoryInterface::class)
            ->give(static function (Application $app) {
                return $app->make(StationRepository::class);
            });

        $this->app->when(StationUpdater::class)
            ->needs(StationRepositoryInterface::class)
            ->give(static function (Application $app) {
                return $app->make(StationRepository::class);
            });

        $this->app->when(StationSearcher::class)
            ->needs(StationRepositoryInterface::class)
            ->give(static function (Application $app) {
                return $app->make(StationRepository::class);
            });

        $this->app->when(StationSearcher::class)
            ->needs(CompanyRepositoryInterface::class)
            ->give(static function (Application $app) {
                return $app->make(CompanyRepository::class);
            });

        $this->app->when(StationStationCacheResponseService::class)
            ->needs(StationRepositoryInterface::class)
            ->give(static function (Application $app) {
                return $app->make(StationRepository::class);
            });

        $this->app->when(StationStationCacheResponseService::class)
            ->needs(StationSearcherInterface::class)
            ->give(static function (Application $app) {
                return $app->make(StationSearcher::class);
            });

        if ($this->app->runningUnitTests()) {
            $this->app->bind(AbstractStationCreator::class, StationCreator::class);
            $this->app->bind(AbstractStationUpdater::class, StationUpdater::class);
            $this->app->bind(AbstractStationDestroyer::class, StationDestroyer::class);
            $this->app->bind(StationRepositoryInterface::class, StationRepository::class);
            $this->app->bind(StationSearcherInterface::class, StationSearcher::class);
        }
        //        SERVICES
    }

    public function boot(): void
    {
        Station::observe(StationObserver::class);

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
