<?php

namespace App\Services\Cache\Models\Projects;

use App\Services\Cache\Models\RedisModel;
use Carbon\Carbon;
use App\Models\Project as ProjectModel;

/**
 * Class Account
 * @package App\Services\Cache\Models\Accounts
 * @property int        $id
 * @property int        $user_id
 * @property int        $address_id
 * @property string     $title
 * @property string     $slug
 * @property int        $featured_weight
 * @property string     $status
 * @property string     $project_status
 * @property string     $description
 * @property string     $type
 * @property int        $bci_id
 * @property \DateTime  $project_end_at
 * @property \DateTime  $project_start_at
 * @property \DateTime  $created_at
 * @property \DateTime  $updated_at
 */
class Project extends RedisModel
{
    protected $id;
    protected $user_id;
    protected $address_id;
    protected $title;
    protected $slug;
    protected $featured_weight;
    protected $status;
    protected $project_status;
    protected $description;
    protected $type;
    protected $project_start_at;
    protected $project_end_at;
    protected $bci_id;
    protected $created_at;
    protected $updated_at;

    protected static $objectProperties = [
        'created_at' => Carbon::class,
    ];

    protected static function newFromServiceByPk($id): ?self
    {
        $project = ProjectModel::find($id);
        if (count(self::$with)) {
            $project = $project->with(self::$with);
        }

        if (isset(self::$filters['nullable'])) {
            $project = $project->first();
        } else {
            $project = $project->firstOrFail();
        }

        if ($project) {
            return new self($project->toArray());
        }

        return null;
    }

    protected static function getCacheTokenPrefix(): string
    {
        return 'project:';
    }

    protected static function getCacheTokenKey(): string
    {
        return 'id';
    }
}
