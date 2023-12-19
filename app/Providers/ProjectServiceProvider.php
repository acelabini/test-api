<?php

namespace App\Providers;

use App\Facades\ProjectServiceFacade;
use App\Services\ProjectService;
use Illuminate\Support\ServiceProvider;

/**
 * Class ProjectServiceProvider
 */
class ProjectServiceProvider extends ServiceProvider
{
    /**
     * Bind facades to their concrete implementations.
     */
    public function register()
    {
        // Bind the cache service
        $this->app->bind(
            ProjectServiceFacade::FACADE_ACCESSOR,
            ProjectService::class
        );
    }
}
