<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectDetail>
 */
class ProjectDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'project_id' => Project::doesntHave('details')->get()->random()->id,
            'number_of_units' => rand(50, 100),
            'levels' => rand(1,20),
            'completion_date' => fake()->dateTimeBetween('-3 years', '+3 years'),
            'website' => fake()->url(),
            'phone' => fake()->phoneNumber(),
            'mobile_number' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'sms_enable' => rand(0, 1),
            'email_enable' => rand(0, 1),
        ];
    }
}
