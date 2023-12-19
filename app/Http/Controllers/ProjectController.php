<?php

namespace App\Http\Controllers;

use App\Http\Resources\Projects\ProjectCollection;
use App\Http\Resources\Projects\ProjectResource;
use App\Models\Project;
use App\Traits\WithCacheService;
use App\Traits\WithProjectService;
use Illuminate\Http\Request;

class ProjectController extends ApiController
{
    use WithCacheService;
    use WithProjectService;

    protected const TAG = 'project';

    /**
     * @OA\Get(
     *     path="/v1/projects",
     *     summary="Get development projects with parameters",
     *     description="Get development projects with parameters",
     *     tags={"PROJECTS"},
     *     security={
     *         { "bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="featured",
     *         in="query",
     *         description="Featured developments",
     *         required=false,
     *         schema={
     *            "type"="string",
     *            "enum"={"true", "false"}
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="randomize",
     *         in="query",
     *         description="Randomize developments",
     *         required=false,
     *         schema={
     *            "type"="boolean",
     *            "enum"={"true", "false"}
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="seed",
     *         in="query",
     *         description="6 digit seed number used randomizing result without duplicate",
     *         required=false,
     *         schema={
     *            "type"="string",
     *            "example"="123456"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Record status (published,unpublished,draft,archived)",
     *         required=false,
     *         schema={
     *            "type"="string"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="projectStatus",
     *         in="query",
     *         description="Project status (pre-selling,ready-for-occupancy)",
     *         required=false,
     *         schema={
     *            "type"="string"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Include project description in the response",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="spaces",
     *         in="query",
     *         description="Response with spaces such as bedrooms, bathrooms, and car spaces",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="prices",
     *         in="query",
     *         description="Include prices in the response",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="properties",
     *         in="query",
     *         description="Include project properties in the response",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="details",
     *         in="query",
     *         description="Response with details",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="amenities",
     *         in="query",
     *         description="Response with amenities",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="address",
     *         in="query",
     *         description="Response with address",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="files",
     *         in="query",
     *         description="Response with files",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="thumbnails",
     *         in="query",
     *         description="Response with thumbnail images",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="articles",
     *         in="query",
     *         description="Response with articles",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="owner",
     *         in="query",
     *         description="Response with owner",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit of results",
     *         required=false,
     *         schema={
     *            "type"="integer",
     *            "example"="4"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Pagination page",
     *         required=false,
     *         schema={
     *            "type"="integer",
     *            "example"="1"
     *         }
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_OK,
     *         description="Developments Record Found.",
     *         @OA\JsonContent(
     *             example={
     *               "success": true,
     *               "message": "Success.",
     *               "data": {
     *                }
     *            }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED,
     *         description="Unauthenticated.",
     *         @OA\JsonContent(
     *             example={
     *                 "success": false,
     *                 "message": "Unauthenticated.",
     *                 "error_code": 401,
     *                 "data": {}
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN,
     *         description="Unauthorized.",
     *         @OA\JsonContent(
     *             example={
     *                  "success": false,
     *                  "message": "Unauthorized.",
     *                  "error_code": 403,
     *                  "data": {}
     *             }
     *         )
     *     )
     * )
     *
     * Get developments with parameters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'limit' => 'integer|max:' . self::MAX_LIMIT,
            'seed' => 'integer|min:6|required_with:randomize',
        ]);

        $projects = self::$cache::remember(
            self::TAG,
            'index',
            fn () => self::$project::filter($request)
        );

        return $this->response(new ProjectCollection($projects));
    }

    /**
     * @OA\Get(
     *     path="/v1/projects/search",
     *     summary="Search development projects with parameters",
     *     description="Search development projects with parameters",
     *     tags={"PROJECTS"},
     *     security={
     *         { "bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by development title, developer, or city",
     *         required=false,
     *         schema={
     *            "type"="string"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="minBedroom",
     *         in="query",
     *         description="Filter by minimum bedroom space",
     *         required=false,
     *         schema={
     *            "type"="integer"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="maxBedroom",
     *         in="query",
     *         description="Filter by maximum bedroom space",
     *         required=false,
     *         schema={
     *            "type"="integer"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="minBathroom",
     *         in="query",
     *         description="Filter by minimum bathroom space",
     *         required=false,
     *         schema={
     *            "type"="integer"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="maxBathroom",
     *         in="query",
     *         description="Filter by maximum bathroom space",
     *         required=false,
     *         schema={
     *            "type"="integer"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="minCarSpace",
     *         in="query",
     *         description="Filter by minimum car space",
     *         required=false,
     *         schema={
     *            "type"="integer"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="maxCarSpace",
     *         in="query",
     *         description="Filter by maximum car space",
     *         required=false,
     *         schema={
     *            "type"="integer"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="minPrice",
     *         in="query",
     *         description="Filter by minimum price",
     *         required=false,
     *         schema={
     *            "type"="integer"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="maxPrice",
     *         in="query",
     *         description="Filter by maximum price",
     *         required=false,
     *         schema={
     *            "type"="integer"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="featuredWeight",
     *         in="query",
     *         description="Order by featured developments",
     *         required=false,
     *         schema={
     *            "type"="string",
     *            "enum"={"true", "false"}
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Project type (condominiums, house-and-lot, townhouses)",
     *         required=false,
     *         schema={
     *            "type"="string"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Record status (published,unpublished,draft,archived)",
     *         required=false,
     *         schema={
     *            "type"="string"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="projectStatus",
     *         in="query",
     *         description="Project status (pre-selling,ready-for-occupancy)",
     *         required=false,
     *         schema={
     *            "type"="string"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Include project description in the response",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="spaces",
     *         in="query",
     *         description="Response with spaces such as bedrooms, bathrooms, and car spaces",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="prices",
     *         in="query",
     *         description="Include prices in the response",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="properties",
     *         in="query",
     *         description="Include project properties in the response",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="details",
     *         in="query",
     *         description="Response with details",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="amenities",
     *         in="query",
     *         description="Response with amenities",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="address",
     *         in="query",
     *         description="Response with address",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="files",
     *         in="query",
     *         description="Response with files",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="thumbnails",
     *         in="query",
     *         description="Response with thumbnail images",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="articles",
     *         in="query",
     *         description="Response with articles",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="owner",
     *         in="query",
     *         description="Response with owner",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="lat",
     *         in="query",
     *         description="Search projects within 1000 radius of the given coordinates",
     *         required=false,
     *         schema={
     *            "type"="string",
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="lng",
     *         in="query",
     *         description="Search projects within 1000 radius of the given coordinates",
     *         required=false,
     *         schema={
     *            "type"="string",
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit of results",
     *         required=false,
     *         schema={
     *            "type"="integer",
     *            "example"="4"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Pagination page",
     *         required=false,
     *         schema={
     *            "type"="integer",
     *            "example"="1"
     *         }
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_OK,
     *         description="Developments Record Found.",
     *         @OA\JsonContent(
     *             example={
     *               "success": true,
     *               "message": "Success.",
     *               "data": {
     *                }
     *            }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED,
     *         description="Unauthenticated.",
     *         @OA\JsonContent(
     *             example={
     *                 "success": false,
     *                 "message": "Unauthenticated.",
     *                 "error_code": 401,
     *                 "data": {}
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN,
     *         description="Unauthorized.",
     *         @OA\JsonContent(
     *             example={
     *                  "success": false,
     *                  "message": "Unauthorized.",
     *                  "error_code": 403,
     *                  "data": {}
     *             }
     *         )
     *     )
     * )
     *
     * Get developments with parameters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function search(Request $request)
    {
        $this->validate($request, [
            'limit' => 'integer|max:' . self::MAX_LIMIT,
            'seed' => 'integer|min:6|required_with:randomize',
        ]);

        $projects = self::$cache::remember(
            self::TAG,
            'search',
            fn () => self::$project::search($request)
        );

        return $this->response(new ProjectCollection($projects));
    }

    /**
     * @OA\Get(
     *     path="/v1/projects/{slug}",
     *     summary="Get development project by UUID with parameters",
     *     description="Get development project by UUID with parameters",
     *     tags={"PROJECTS"},
     *     security={
     *         { "bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="Project slug",
     *         required=true,
     *         schema={
     *            "type"="string"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Include project description in the response",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="spaces",
     *         in="query",
     *         description="Response with spaces such as bedrooms, bathrooms, and car spaces",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="prices",
     *         in="query",
     *         description="Include prices in the response",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="properties",
     *         in="query",
     *         description="Include project properties in the response",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="details",
     *         in="query",
     *         description="Response with details",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="amenities",
     *         in="query",
     *         description="Response with amenities",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="address",
     *         in="query",
     *         description="Response with address",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="files",
     *         in="query",
     *         description="Response with files",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="thumbnails",
     *         in="query",
     *         description="Response with thumbnail images",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="articles",
     *         in="query",
     *         description="Response with articles",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="owner",
     *         in="query",
     *         description="Response with owner",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="unit_images",
     *         in="query",
     *         description="Response with unit_images",
     *         required=false,
     *         schema={
     *            "type"="boolean"
     *         }
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_OK,
     *         description="Developments Record Found.",
     *         @OA\JsonContent(
     *             example={
     *               "success": true,
     *               "message": "Success.",
     *               "data": {
     *                }
     *            }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED,
     *         description="Unauthenticated.",
     *         @OA\JsonContent(
     *             example={
     *                 "success": false,
     *                 "message": "Unauthenticated.",
     *                 "error_code": 401,
     *                 "data": {}
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN,
     *         description="Unauthorized.",
     *         @OA\JsonContent(
     *             example={
     *                  "success": false,
     *                  "message": "Unauthorized.",
     *                  "error_code": 403,
     *                  "data": {}
     *             }
     *         )
     *     )
     * )
     *
     * Get development by ID with parameters
     *
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show(Request $request, Project $project)
    {
        $project = self::$cache::remember(
            self::TAG,
            "show-{$project->id}",
            fn () => new ProjectResource($project)
        );

        return $this->response($project);
    }
}
