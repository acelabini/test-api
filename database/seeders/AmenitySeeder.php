<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Project;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var Project $project */
        foreach (Project::all() as $project) {
            $amenity1 = Amenity::create([
                'label'      => 'Clubhouse',
                'icon_class' => 'OtherHousesOutlinedIcon'
            ]);
            $amenity2 = Amenity::create([
                'label'      => 'Swimming pool',
                'icon_class' => 'PoolOutlinedIcon'
            ]);
            $amenity3 = Amenity::create([
                'label'      => "Children's Park",
                'icon_class' => 'PoolOutlinedIcon'
            ]);
            $project->amenities()->attach($amenity1);
            $project->amenities()->attach($amenity2);
            $project->amenities()->attach($amenity3);
        }
    }
}
