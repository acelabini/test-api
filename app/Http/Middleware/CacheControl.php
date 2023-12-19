<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class CacheControl
{
    /**
     * Handle an incoming request.
     *
    /**
     * @param $request
     * @param Closure $next
     * @param $ttlInMinutes
     * @return mixed
     */
    public function handle($request, Closure $next, $ttlInMinutes = null)
    {
        $key = $this->getRequestKey($request);
        $ttl = $ttlInMinutes ?: (env('CACHE_TTL', 86400) / 60);
        $isRequestingCacheUpdate = $request->header('cache-invalidate');

        if ( ! $isRequestingCacheUpdate) {
            $cachedData = $this->get($key);
            if ($cachedData) {
                return $cachedData;
            }
        }

        $response = $next($request);

        try {
            $this->set($key, $response, $ttl);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
        }

        return $response;
    }

    private function get($key)
    {
        $data = Redis::get($key);
        return unserialize($data);
    }

    private function set($key, $data, $expiry)
    {
        Redis::set($key, serialize($data));
        Redis::expire($key, $this->minToSeconds($expiry));
    }

    /**
     * Generate a cache key from a request.
     *
     * @param Request $request
     * @return string
     */
    private function getRequestKey(Request $request): string
    {
        $method = $request->getMethod();
        $uri    = $request->getRequestUri();
        return sprintf('req:%s:%s', $method, $uri);
    }

    /**
     * Convert minutes to seconds
     *
     * @param int $minutes
     * @return int
     */
    private function minToSeconds(int $minutes): int
    {
        return $minutes * 60;
    }
}
