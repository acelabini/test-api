<?php

namespace App\Traits;

use App\Facades\ProjectServiceFacade;
use App\Services\ProjectService;

trait WithProjectService
{
    /** @var ProjectService */
    protected static $project = ProjectServiceFacade::class;
}
