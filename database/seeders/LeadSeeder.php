<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Lead;
use App\Models\LeadContact;
use App\Models\LeadUtm;
use App\Models\Project;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $project = Project::first();

        /* @var LeadContact $leadContact */
        $leadContact = LeadContact::create([
            'full_name' => 'Test User Lastname',
            'mobile'    => '09081234567',
            'email'     => 'testemail123@email.c'
        ]);

        /* @var Lead $lead */
        $lead = Lead::create([
            'form_data' => '{"which_describe_you_best": "investor", "when_are_you_planning_to_buy": "1 - 3 Months"}',
            'lead_contact_id' => $leadContact->id,
            'project_id' => $project->id
        ]);

        $leadUtm = LeadUtm::create([
            'source' => 'byldan',
            'medium' => 'lead_form',
            'lead_id' => $lead->id
        ]);
    }
}
