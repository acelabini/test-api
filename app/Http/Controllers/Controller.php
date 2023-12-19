<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Byldan Development API",
 *      description="Byldan Development API"
 * ),
 * @OA\SecurityScheme(
 *     @OA\Flow(
 *         flow="clientCredentials",
 *         tokenUrl="/oauth/token",
 *         scopes={}
 *     ),
 *     securityScheme="bearerAuth",
 *     in="header",
 *     type="oauth2",
 *     description="Creates a POST request to /oauth/token and respond a token to use as Bearer Token.",
 *     name="oauth2",
 *     scheme="http",
 *     bearerFormat="bearer",
 * ),
 * @OA\Schema(
 *   schema="pagination",
 *   title="Pagination",
 *   type="object",
 *   description="Amenity Object Response",
 *   @OA\Property(property="total", type="integer"),
 *   @OA\Property(property="count", type="integer"),
 *   @OA\Property(property="perPage", type="integer"),
 *   @OA\Property(property="currentPage", type="integer"),
 *   @OA\Property(property="totalPages", type="integer"),
 * ),
 * @OA\Schema(
 *   schema="notFound",
 *   title="Record Not Found",
 *   type="object",
 *   description="Record Not Found Response",
 *   @OA\Property(property="success", type="boolean"),
 *   @OA\Property(property="message", type="string"),
 *   @OA\Property(property="error_code", type="integer"),
 * ),
 * @OA\Schema(
 *   schema="unauthenticated",
 *   title="Unauthenticated",
 *   type="object",
 *   description="Unauthenticated Response",
 *   @OA\Property(property="success", type="boolean"),
 *   @OA\Property(property="message", type="string"),
 *   @OA\Property(property="error_code", type="integer"),
 * ),
 * @OA\Schema(
 *   schema="unauthorized",
 *   title="Unauthorized",
 *   type="object",
 *   description="Unauthorized Response",
 *   @OA\Property(property="success", type="boolean"),
 *   @OA\Property(property="message", type="string"),
 *   @OA\Property(property="error_code", type="integer"),
 * ),
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
