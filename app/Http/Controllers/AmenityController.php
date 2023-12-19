<?php

namespace App\Http\Controllers;

use App\Http\Requests\AmenityRequest;
use App\Http\Resources\AmenityCollection;
use App\Http\Resources\AmenityResource;
use App\Models\Amenity;
use App\Traits\WithCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class AmenityController extends ApiController
{
    use WithCacheService;

    const DEFAULT_LIMIT = 10;

    protected const TAG = 'amenity';

    /**
     * @OA\Get(
     *     path="/v1/management/amenities",
     *     summary="Get all amenities with parameters",
     *     description="Get all amenities with parameters",
     *     tags={"AMENITIES"},
     *     security={
     *         { "bearerAuth": {}}
     *     },
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
     *         description="Amenities Record Found.",
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
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'limit' => 'integer|max:'.self::MAX_LIMIT,
        ]);

        return $this->response(new AmenityCollection(
            Amenity::paginate($request->query('limit', self::DEFAULT_LIMIT))
        ));
    }

    /**
     * @OA\Post(
     *     path="/v1/management/amenities",
     *     summary="Store amenity with request body",
     *     description="Store amenity with request body",
     *     tags={"AMENITIES"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AmenityRequest")
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_OK,
     *         description="Amenities Record Created.",
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
     * Store a newly created resource in storage.
     *
     * @param AmenityRequest $request
     *
     * @return JsonResponse
     */
    public function store(AmenityRequest $request)
    {
        $createdAmenity = $this->saveModel(new Amenity(), $request->validated());

        $amenity = self::$cache::remember(
            self::TAG,
            "show-{$createdAmenity->id}",
            fn() => new AmenityResource($createdAmenity)
        );

        return $this->response($amenity);
    }

    /**
     * @OA\Get(
     *     path="/v1/management/amenities/{id}",
     *     summary="Get amenity by ID",
     *     description="Get amenity by ID",
     *     tags={"AMENITIES"},
     *     security={
     *         { "bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Amenity ID",
     *         required=true,
     *         schema={
     *            "type"="string"
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
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show(string $id): JsonResponse
    {
        $amenity = self::$cache::remember(
            self::TAG,
            "show-{$id}",
            fn() => new AmenityResource(Amenity::findOrFail($id))
        );

        return $this->response($amenity);
    }

    /**
     * @OA\Put(
     *     path="/v1/management/amenities/{id}",
     *     summary="Update amenity with request body",
     *     description="Update amenity with request body",
     *     tags={"AMENITIES"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Amenity ID",
     *         required=true,
     *         schema={
     *            "type"="string"
     *         }
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AmenityRequest")
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_OK,
     *         description="Amenities Record Updated.",
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
     * Update the specified resource in storage.
     *
     * @param AmenityRequest $request
     * @param  string  $id
     *
     * @return JsonResponse
     */
    public function update(AmenityRequest $request, string $id)
    {
        $createdAmenity = $this->saveModel(Amenity::findOrFail($id), $request->validated());

        $amenity = self::$cache::remember(
            self::TAG,
            "show-{$createdAmenity->id}",
            fn() => new AmenityResource($createdAmenity)
        );

        return $this->response($amenity);
    }

    /**
     * @OA\Delete(
     *     path="/v1/management/amenities/{id}",
     *     summary="Delete amenity using id",
     *     description="Delete amenity using id",
     *     tags={"AMENITIES"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Amenity ID",
     *         required=true,
     *         schema={
     *            "type"="string"
     *         }
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_OK,
     *         description="Amenities Record Deleted.",
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
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     *
     * @return JsonResponse
     */
    public function destroy(string $id)
    {
        $amenity = Amenity::findOrFail($id);

        $amenity->delete();

        return $this->response(['success' => true]);
    }

    /**
     * @param Amenity $amenity
     * @param array   $validatedAmenityForm
     *
     * @return Amenity
     */
    private function saveModel(Amenity $amenity, array $validatedAmenityForm)
    {
        $amenity->label = $validatedAmenityForm['label'];
        $amenity->icon_class = $validatedAmenityForm['icon_class'];

        $amenity->save();

        return $amenity;
    }
}
