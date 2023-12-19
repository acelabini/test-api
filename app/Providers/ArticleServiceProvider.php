<?php

namespace App\Providers;

use App\Facades\ArticleServiceFacade;
use App\Services\ArticleService;
use Illuminate\Support\ServiceProvider;

/**
 * Class ArticleServiceProvider
 */
class ArticleServiceProvider extends ServiceProvider
{
  /**
   * Bind facades to their concrete implementations.
   */
  public function register()
  {
    // Bind the cache service
    $this->app->bind(
      ArticleServiceFacade::FACADE_ACCESSOR,
      ArticleService::class
    );
  }
}
