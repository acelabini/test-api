<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectProperty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectProperty>
 */
class ProjectPropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $areaTotal = fake()->randomFloat(2, 20, 100);
        $price = rand(1, 100) > 80 ? 0 : rand(1000000, 10000000);
        return [
            'project_id' => Project::all()->random()->id,
            'title' => ucwords(fake()->words(asText: true)),
            'status' => ProjectProperty::PUBLISHED,
            'area_total' => $areaTotal,
            'area_external' => fake()->randomFloat(2, 20, $areaTotal),
            'area_internal' => fake()->randomFloat(2, 20, $areaTotal),
            'bedrooms' => rand(1, 5),
            'bathrooms' => rand(1, 3),
            'car_spaces' => rand(0, 3),
            'levels' => rand(1,3),
            'price' => $price,
            'deposit_payment' => $price ? (rand(1, 100) > 80 ? 0 : $price * (rand(0, 10) / 10)) : 0,
            'monthly_payment' => $price ? rand(20000, 100000) : 0,
        ];
    }
}
