<?php

namespace App\Services\Cache\Models;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as BaseCollection;

class Collection extends BaseCollection
{
    /**
     * @var string $childClass Defines the class that children must be an instance of.
     */
    protected static $childClass = null;
    /**
     * @var string $childKey Defines the property that children should be keyed by when flattened.
     */
    protected static $childKey = null;

    public function paginate($perPage = 10, $page = 1)
    {
        return new LengthAwarePaginator($this->forPage($page, $perPage), $this->count(), $perPage, $page);
    }

    /**
     * Create a new collection, enforcing child type.
     *
     * @param  mixed $items
     * @return static|null
     */
    public static function load($items = [])
    {
        if (is_null($items)) {
            return null;
        }
        if ( ! is_null(static::$childClass)) {
            foreach ($items as $k => $item) {
                if ( ! ($item instanceof static::$childClass)) {
                    $items[$k] = new static::$childClass($item);
                }
            }
        }

        return new static($items);
    }

    /**
     * Get Flatten Key
     * Get the property that children should be keyed by when flattened
     *
     * @return string|null
     */
    public static function getFlattenKey() : ?string
    {
        return static::$childKey;
    }

    /**
     * __sleep
     * @NOTICE: Define which properties to persist in the cache store.
     *
     * @return array
     */
    public function __sleep()
    {
        return ['items'];
    }
}
