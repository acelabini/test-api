<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectService
{
    const DEFAULT_LIMIT = 10;

    public static function filter(Request $request): LengthAwarePaginator
    {
        $query = Project::withFilters(
            $request->all(),
            $request->only([
                'owner',
                'details',
                'address',
                'files',
                'thumbnails'
            ])
        );
        $projects = $query->clone()->paginate($request->query('limit', self::DEFAULT_LIMIT));
        $projects->totalProperties = Project::totalUnits($query->clone());

        return $projects;
    }

    public static function search(Request $request)
    {
        $query = Project::searchWithFilter(
            $request->query('search', ''),
            $request->all()
        );
        $projects = $query->paginate($request->query('limit', self::DEFAULT_LIMIT));
        $projects->load(filter_request([
            'owner',
            'address',
            'files',
            'thumbnails'
        ]) + ['properties', 'details', 'address']);
        $projects->totalProperties = $query->get()->sum('details.number_of_units');

        return $projects;
    }
}
