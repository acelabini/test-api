<?php

namespace App\Services\Cache;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache as Redis;

class CacheService
{
    public static function resource(
        string $resource,
        Model|Collection|array $request,
        $key = null,
        $includeRequest = false
    ) {
        $isModel = $request instanceof Model;
        $id = $isModel ? $request->id : $key;

        return self::remember(
            'resource',
            "{$resource}-{$id}",
            fn() => $isModel ? new $resource($request) : $resource::collection($request),
            includeRequest: $includeRequest
        );
    }

    public static function remember(
        string $tag,
        string $key,
        \Closure $callback,
        Carbon|string $lifetime = null,
        bool $includeRequest = true
    ) {
        $request = request();
        $keys = $includeRequest ? implode("-", $request->keys()) . implode("-", $request->all()) : null;

        return Redis::tags([$tag, $key])->remember(
            $key . $keys,
            $lifetime ?: now()->addMinutes(config('cache.stores.redis.lifetime')),
            fn () => $callback()
        );
    }
}
