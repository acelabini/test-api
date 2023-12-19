<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = fake()->words(asText: true);
        $startDate = fake()->dateTimeBetween('-3 years');

        return [
            'user_id' => User::all()->random()->id,
            'address_id' => Address::all()->random()->id,
            'title' => ucwords($title),
            'slug' => Str::slug($title),
            'featured_weight' => rand(50, 100),
            'status' => 'published',
            'project_status' => fake()->randomElement(Project::PROJECT_STATUSES),
            'description' => fake()->paragraphs(asText: true),
            'type' => fake()->randomElement(Project::TYPES),
            'project_start_at' => $startDate,
            'project_end_at' => fake()->dateTimeBetween($startDate, '+3 years')
        ];
    }
}
