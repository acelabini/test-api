<?php

namespace App\Traits;

use App\Facades\ArticleServiceFacade;
use App\Services\ArticleService;

trait WithArticleService
{
  /** @var ArticleService */
  protected static $article = ArticleServiceFacade::class;
}
