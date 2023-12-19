<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Models\Lead;
use App\Models\LeadContact;
use App\Models\LeadUtm;
use App\Models\Project;
use App\Traits\WithCacheService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class LeadController extends ApiController
{
    use WithCacheService;

    protected const TAG = 'lead';

    /**
     * @OA\Post(
     *     path="/v1/projects/lead",
     *     summary="Store lead request form data",
     *     description="Store lead request form data",
     *     tags={"LEADS"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LeadRequest")
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
     * @param LeadRequest $request
     *
     * @return JsonResponse
     */
    public function store(LeadRequest $request)
    {
        $leadFormData = $request->validated();

        $project = Project::findOrFail($leadFormData['project_id']);

        $leadContact = LeadContact::create([
            'full_name' => $leadFormData['full_name'],
            'mobile'    => $leadFormData['mobile'],
            'email'     => $leadFormData['email']
        ]);

        $lead = new Lead();
        $lead->form_data = json_encode($leadFormData);
        $lead->lead_contact_id = $leadContact->id;
        $lead->project_id = $project->id;
        $lead->save();

        $leadUtm = new LeadUtm();
        $leadUtm->source = 'byldan';
        $leadUtm->medium = 'lead_form';
        $leadUtm->lead_id = $lead->id;
        $leadUtm->save();

        return $this->response(['success' => true]);
    }
}
