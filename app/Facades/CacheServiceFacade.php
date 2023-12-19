<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class CacheServiceFacade
 */
class CacheServiceFacade extends Facade
{
    const FACADE_ACCESSOR = 'cacheservice';

    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return self::FACADE_ACCESSOR;
    }
}
