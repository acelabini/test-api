<?php

namespace App\Providers;

use App\Facades\CacheServiceFacade;
use App\Services\Cache\CacheService;
use Illuminate\Support\ServiceProvider;

/**
 * Class CacheServiceProvider
 */
class CacheServiceProvider extends ServiceProvider
{
    /**
     * Bind facades to their concrete implementations.
     */
    public function register()
    {
        // Bind the cache service
        $this->app->bind(
            CacheServiceFacade::FACADE_ACCESSOR,
            CacheService::class
        );
    }
}
