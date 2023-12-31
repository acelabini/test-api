<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ProjectServiceFacade
 */
class ProjectServiceFacade extends Facade
{
    const FACADE_ACCESSOR = 'projectservice';

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
