<?php

namespace App\Traits;

use App\Facades\CacheServiceFacade;
use App\Services\Cache\CacheService;

trait WithCacheService
{
    /** @var CacheService */
    protected static $cache = CacheServiceFacade::class;
}
