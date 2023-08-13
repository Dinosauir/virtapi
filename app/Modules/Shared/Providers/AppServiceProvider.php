<?php

namespace App\Modules\Shared\Providers;

use App\Modules\Shared\Exceptions\Handler;
use App\Modules\Shared\Services\ExceptionHandlerService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
