<?php

namespace App\Virtual;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      title="Lead request",
 *      description="Lead request body data",
 *      type="",
 *      required={"project_id", "form_data", "full_name", "mobile", "email"}
 * )
 */
class LeadRequest
{
    /**
     * @OA\Property(
     *      title="project_id",
     *      description="The project id which should be a UUID",
     *      example="05d7ddfd-ba03-467f-946c-a82d216026aa"
     * )
     */
    public $project_id;

    /**
     * @OA\Property(
     *      title="form_data",
     *      description="A json that consist of `which_describe_you_best` and `when_are_planning_to_buy`",
     *      example={"which_describe_you_best": "first_home_buyers", "when_are_you_planning_to_buy": "1 - 3 Months"}
     * )
     */
    public $form_data;

    /**
     * @OA\Property(
     *      title="full_name",
     *      description="Person's full name",
     *      example="Juan San Jose"
     * )
     */
    public $full_name;

    /**
     * @OA\Property(
     *      title="mobile",
     *      description="A valid mobile in the country",
     *      example="09081234567"
     * )
     */
    public $mobile;

    /**
     * @OA\Property(
     *      title="email",
     *      description="A valid email address",
     *      example="myemail@domain.com"
     * )
     */
    public $email;
}
