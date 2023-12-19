<?php

use Illuminate\Http\Request;

if (!function_exists("show_resource")) {
    /**
     * @param $value
     * @return bool
     */
    function show_resource($value): bool
    {
        /** @var Request $request */
        $request = request();

        return $request->has($value) ? is_true($request->get($value)) : false;
    }
}

if (!function_exists("is_true")) {
    /**
     * @param $value
     * @return bool
     */
    function is_true($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}

if (!function_exists("filter_request")) {
    /**
     * @param array $keys
     * @return array
     */
    function filter_request(array $keys): array
    {
        $request = request();

        return array_keys($request->only($keys));
    }
}
