<?php

namespace App\Services;

use App\Models\Article;
use Carbon\Carbon;

class ArticleService
{
    /**
     * @param Article $article
     * @param string $status
     *
     * @return Article
     */
    public static function patch(Article $article, string $status)
    {
        $article->status = $status;

        if ($status === Article::PUBLISHED) {
          $article->published_at = Carbon::now();
        }

        $article->save();

        return $article;
    }
}
