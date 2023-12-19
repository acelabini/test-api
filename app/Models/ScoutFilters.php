<?php

namespace App\Models;

use Laravel\Scout\Builder;
use MeiliSearch\Endpoints\Indexes;

trait ScoutFilters
{
    public static function searchWithFilter($query = '', $filter = []): Builder
    {
        return self::search(
            $query,
            function ( Indexes $indexes, string $query, array $options) use ($filter) {
                $options['filter'] = self::generateFiltering($filter);
                $options['sort'] = self::generateSorting($filter);

                return $indexes->search( $query, $options );
            }
        );
    }

    public static function getSearchableAttributes(): array
    {
        return self::$searchable;
    }

    public static function getFilterableAttributes(): array
    {
        return self::$filterable;
    }

    public static function getSortableAttributes(): array
    {
        return self::$sortable;
    }

    private static function generateSorting($filterFromRequest = []): array
    {
        $generatedSorting = [];
        foreach (self::$sortable as $sortable => $order) {
            // convert filterable to camelCase
            $formattedKey = lcfirst(
                str_replace('_', '', ucwords($sortable, '_'))
            );
            if (isset($filterFromRequest[$formattedKey]) && is_true($filterFromRequest[$formattedKey])) {
                $generatedSorting[] = $sortable.':'.$order;
            }
        }

        return $generatedSorting;
    }

    private static function generateFiltering($filterFromRequest = []): array
    {
        $generatedFilters = [];
        foreach (self::$filterable as $filterable => $operator) {
            // convert filterable to camelCase
            $formattedKey = lcfirst(
                str_replace('_', '', ucwords($filterable, '_'))
            );
            // special case, _geo is not using operator
            if ($filterable === '_geo') {
                $generatedFilters[] = self::filterRadius($filterFromRequest);
                continue;
            }
            if (isset($filterFromRequest[$formattedKey])) {
                $generatedFilters[] = $filterable.' '.$operator.' '.$filterFromRequest[$formattedKey];
            }
        }

        return array_values(array_filter($generatedFilters));
    }

    private static function filterRadius($filterFromRequest): ?string
    {
        if (isset($filterFromRequest['lat']) && isset($filterFromRequest['lng'])) {
            $radius = config('byldan.max_search_radius');
            return "_geoRadius({$filterFromRequest['lat']}, {$filterFromRequest['lng']}, {$radius})";
        }

        return null;
    }
}
