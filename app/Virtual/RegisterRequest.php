<?php

namespace App\Virtual;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      title="Register request",
 *      description="Registration register request body data to create new user",
 *      type="object",
 *      required={
 *        "first_name",
 *        "middle_name",
 *        "last_name",
 *        "email",
 *        "mobile_prefix",
 *        "mobile_number",
 *        "password",
 *        "role_id"
 *      }
 * )
 */
class RegisterRequest
{
    /**
     * @OA\Property(
     *      example="John"
     * )
     *
     * @var string
     */
    public $first_name;

    /**
     * @OA\Property(
     *     example="Lee"
     * )
     *
     * @var string
     */
    public $middle_name;

    /**
     * @OA\Property(
     *     example="Doe"
     * )
     *
     * @var string
     */
    public $last_name;

    /**
     * @OA\Property(
     *     example="sample@address.com"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *     example="+63"
     * )
     *
     * @var string
     */
    public $mobile_prefix;

    /**
     * @OA\Property(
     *     example="9123456780"
     * )
     *
     * @var string
     */
    public $mobile_number;

    /**
     * @OA\Property(
     *     description="Minimum of 8 characters",
     *     example="Demo1234"
     * )
     *
     * @var string
     */
    public $password;

    /**
     * @OA\Property(
     *     description="Must exist in role table using its id",
     *     example="70a8513d-0ac6-4c31-9087-e5b487edda2e"
     * )
     *
     * @var string
     */
    public $role_id;
}
