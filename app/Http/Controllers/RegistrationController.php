<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\RegistrationService;
use App\Traits\WithCacheService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class RegistrationController extends ApiController
{
    use WithCacheService;

    protected const TAG = 'user';

    public function __construct(private RegistrationService $registrationService)
    {

    }

    /**
     * @OA\Post(
     *     path="/v1/registration",
     *     summary="Register a new user",
     *     tags={"REGISTRATION"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *     @OA\Response(
     *        response=Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY,
     *        description="Error in passing JSON data",
     *        @OA\JsonContent(
     *            example={
     *              "message": "The mobile number must be in the proper format and must be between 12 and 19 characters. (and 1 more error)",
     *              "errors": {
     *                  "mobile_number": {
     *                      "The mobile number must be in the proper format and must be between 12 and 19 characters."
     *                  },
     *                  "role_id": {
     *                      "The selected role id is invalid."
     *                  }
     *              }
     *          }
     *        )
     *     ),
     *     @OA\Response(
     *        response=Symfony\Component\HttpFoundation\Response::HTTP_OK,
     *        description="User Registered.",
     *        @OA\JsonContent(
     *            example={
     *              "success": true,
     *              "message": "Success.",
     *              "data": {
     *                 "id": "ed7dd38e-4c79-4982-bada-82f4dffb1018",
     *                 "firstName": "First Name",
     *                 "middleName": "MiddleName",
     *                 "lastName": "LastName",
     *                 "email": "email@address.com",
     *                 "mobilePrefix": "+63",
     *                 "mobileNumber": "1234567890",
     *                 "verifiedAt": null
     *               }
     *           }
     *        )
     *     ),
     * )
     *
     * @param RegisterRequest $request
     *
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $registeredUser = $this->registrationService->register($request->validated());

        $user = self::$cache::remember(
            self::TAG,
            "show-{$registeredUser->id}",
            fn() => new UserResource($registeredUser)
        );

        return $this->response($user);
    }
}
