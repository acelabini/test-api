<?php

namespace App\Services\Cache\Models;

use Illuminate\Support\Facades\Redis;

abstract class RedisModel extends BaseModel
{
    protected static $filters = [];

    /**
     * Reload from Source.
     * @see static::newFromServiceByPk
     *
     * @param int|string $id
     * @return static|null
     */
    public static function reload($id): ?self
    {
        $model = static::newFromServiceByPk($id);
        if ($model) {
            $model->save();
        }

        return $model;
    }

    // ------------------------------
    // Redis Wrapper Methods.
    // ------------------------------

    /**
     * Get from Redis (or from Source if not found in Redis)
     *
     * @param int|string $id
     * @return static|null
     */
    public static function get($id): ?self
    {
        $class = get_called_class();

        /** @noinspection PhpUndefinedMethodInspection */
        $raw = Redis::get(static::makeToken($id));

        if (!$raw) {
            $model = static::reload($id);
            if ($model) {
                $model->save();
            }

            return $model;
        }

        return new $class(unserialize($raw));
    }

    /**
     * Save to Redis
     *
     * @return bool
     */
    public function save(): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return Redis::set(static::getToken(), $this->__toString())
            && Redis::expire(static::getToken(), env('CACHE_TTL', 86400));
    }

    /**
     * Delete from Redis
     *
     * @return int
     */
    public function delete(): int
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return Redis::del(static::getToken());
    }

    /**
     * @param $id
     * @return bool
     */
    public static function exist($id): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return ! empty(Redis::get(static::makeToken($id)));
    }

    /**
     * Set filters
     *
     * @param array $filters
     * @return static
     */
    public static function setFilters(array $filters = []): self
    {
        self::$filters = $filters;

        return new static([]);
    }

    /**
     * Set relationship
     *
     * @param array $with
     * @return static
     */
    public static function setRelation($with): self
    {
        self::$with = $with;
        return new static([]);
    }

    // ------------------------------
    // Token Helper Methods.
    // ------------------------------

    /**
     * Make Token
     * Combines the Cache Token Prefix and $id to generate a full Redis Cache Token.
     *
     * @param $id
     * @return string
     */
    protected static function makeToken($id): string
    {
//        $identifiers = '';
//        if (count(self::$filters)) {
//            $identifiers = implode('_', self::$filters);
//        }

        return static::getCacheTokenPrefix() . $id;
    }

    /**
     * Get Token
     * Get the Token for this object.
     *
     * @return string
     */
    protected function getToken(): string
    {
        return static::makeToken($this->{static::getCacheTokenKey()});
    }

    /**
     * Create New instance from external Service by Primary Key.
     *
     * @param $id
     * @return mixed
     */
    protected abstract static function newFromServiceByPk($id);

    /**
     * Get Cache Token Prefix
     * @NOTICE: This defines the prefix to the key that will be used to identify this object in Redis
     *
     * @return string
     */
    protected abstract static function getCacheTokenPrefix(): string;

    /**
     * Get Cache Token Key
     * @NOTICE: This defines the property used as the unique key to identify this object in Redis.
     *
     * @return string
     */
    protected abstract static function getCacheTokenKey(): string;

}
