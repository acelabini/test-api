<?php

namespace App\Services\Cache\Models;

use \Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Log;

abstract class BaseModel implements \ArrayAccess, Arrayable
{
    const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var array $ignoreProperties Defines which properties to not persist in Redis.
     */
    protected static $ignoreProperties = [];

    /**
     * @var array $objectProperties Defines which properties to are objects that need to be rolled up.
     */
    protected static $objectProperties = [];

    /**
     * @var array
     */
    protected static $with = [];


    protected static $primaryKey = 'id';

    /**
     * Constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->fill($attributes);
    }

    /**
     * Fill the employee
     * @param array $attributes
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->__set($key, $value);
        }
    }

    /**
     * Merge the given attributes to the existing one.
     *
     * @param array $attributes
     */
    public function merge(array $attributes)
    {
        $this->fill(array_merge($this->toArray(), $attributes));
    }

    /**
     * __get Magic Method.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            if (!$this->$name) {
                self::$with[$name] = true;
            }

            return $this->$name;
        } elseif (method_exists($this, $name)) {
            return $this->$name();
        }

        return null;
    }

    /**
     * @param $name
     */
    public function __unset($name)
    {
        unset($this->$name);
    }

    /**
     * __set Magic Method.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            if ( ! is_null($value)
                && array_key_exists($name, static::$objectProperties)
            ) {
                if (is_array(static::$objectProperties[$name])) {
                    [$class, $foreignKey] = static::$objectProperties[$name];
                    $value = is_subclass_of($class, RedisModel::class)
                        ? method_exists($class, 'get')
                            ? call_user_func(
                                [$class, 'get'],
                                is_array($value) ? $this->{$foreignKey} : $value
                            ) : new $class($value)
                        : new $class($value);
                } elseif (! ($value instanceof static::$objectProperties[$name])) {
                    if ((is_subclass_of(static::$objectProperties[$name], Collection::class))) {
                        $value = call_user_func([static::$objectProperties[$name], 'load'], $value);
                    } else {
                        $value = is_array($value) ? (count($value) ? $value : $this->{self::$primaryKey})
                            : ($value ?: $this->{self::$primaryKey});
                        $value = new static::$objectProperties[$name]($value);
                    }
                }
            }

            $this->$name = $value;
        }
    }

    /**
     * __sleep
     * @NOTICE: Define which properties to persist in the cache store.
     *
     * @return array
     */
    public function __sleep()
    {
        $arr = array_diff_key(
            get_object_vars($this),
            array_flip(static::$ignoreProperties),
            ['ignoreProperties' => 1, 'objectProperties' => 1]);

        return array_keys($arr);
    }

    /**
     * __toString
     * @NOTICE: Object must be converted to string before saving to Redis.
     *
     * @return string
     */
    public function __toString(): string
    {
        return serialize($this->all());
    }

    /**
     * Get Attributes
     *
     * @return array
     */
    public function getAttributes() : array
    {
        return $this->all();
    }

    /**
     * all
     * @NOTICE: Excludes ignored properties
     * @NOTICE: Flattens embedded documents and collections.
     *
     * @return array
     */
    public function all() : array
    {
        $arr = array_diff_key(
            get_object_vars($this),
            array_flip(static::$ignoreProperties),
            ['ignoreProperties' => 1, 'objectProperties' => 1]);
        foreach (array_keys(static::$objectProperties) as $key) {
            if (isset($arr[$key])) {
                if ($arr[$key] instanceof Collection) {
                    /** @var Collection $arr [$key] */
                    $keyName   = $arr[$key]::getFlattenKey();
                    $arr[$key] = ! is_null($keyName) ? $arr[$key]->keyBy($keyName)->all() : $arr[$key]->all();
                    if ( ! empty($arr[$key])) {
                        foreach ($arr[$key] as $k => $v) {
                            if (is_object($v) && method_exists($v, 'all')) {
                                /** @var Collection $v */
                                $arr[$key][$k] = $v->all();
                            }
                        }
                    }
                } elseif (method_exists($arr[$key], 'all')) {
                    $arr[$key] = $arr[$key]->all();
                } elseif ($arr[$key] instanceof \DateTime) {
                    /** @var \DateTime $dateTime */
                    $dateTime  = $arr[$key];
                    $arr[$key] = $dateTime->format(self::DATE_TIME_FORMAT);
                }
            }
        }

        return $arr;
    }

    /**
     * Get the instance as an array.
     * @see Arrayable
     *
     * @return array
     */
    public function toArray()
    {
        return $this->all();
    }

    /**
     * Whether a offset exists
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     * @return boolean true on success or false on failure.
     *                      </p>
     *                      <p>
     *                      The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return property_exists($this, $offset);
    }

    /**
     * Offset to retrieve
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * Offset to set
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    /**
     * Offset to unset
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        $this->$offset = null;
    }
}
