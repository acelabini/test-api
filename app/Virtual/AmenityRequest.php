<?php

namespace App\Virtual;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      title="Amenity request",
 *      description="Amenity request body data",
 *      type="object",
 *      required={"label", "icon_class"}
 * )
 */
class AmenityRequest
{
    /**
     * @OA\Property(
     *      title="label",
     *      description="Label of the new amenity",
     *      example="Pool"
     * )
     *
     * @var string
     */
    public $label;

    /**
     * @OA\Property(
     *      title="icon_class",
     *      description="Icon class name that can be found in MuiIcons",
     *      example="PoolOutlinedIcon"
     * )
     *
     * @var string
     */
    public $icon_class;
}
