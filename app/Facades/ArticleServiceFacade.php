<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ArticleServiceFacade
 */
class ArticleServiceFacade extends Facade
{
  const FACADE_ACCESSOR = 'articleservice';

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
