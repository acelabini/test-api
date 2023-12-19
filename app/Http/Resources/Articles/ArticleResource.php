<?php

namespace App\Http\Resources\Articles;

use App\Http\Resources\Projects\ProjectCollection;
use App\Traits\WithCacheService;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
  use WithCacheService;

  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
      'category'    => new ArticleCategoryResource($this->category),
      'title'       => $this->title,
      'slug'        => $this->slug,
      'content'     => $this->content,
      'status'      => $this->status,
      'publishedAt' => $this->published_at->format('Y-m-d'),
      'projects'    => new ProjectCollection($this->projects),
      'images'      => $this->getThumbnails(),
    ];
  }

  private function getThumbnails(): mixed
  {
    return self::$cache::remember(
      'article',
      "files-thumbnails-{$this->id}",
      fn () => preg_filter('/^/', config('byldan.cdn'), $this->thumbnails?->pluck('path')?->toArray()),
    );
  }
}
