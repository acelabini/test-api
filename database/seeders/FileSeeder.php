<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $files = \Illuminate\Support\Facades\File::allFiles(public_path('images'));
        if (!count($files)) {
            return;
        }
        /** @var Project $project */
        foreach (Project::all() as $project) {
            /** @var SplFileInfo $file */
            $file = Arr::random($files);
            $fileName = $project->slug."-".Str::random(6).".{$file->getExtension()}";
            $uploadedPath = Storage::putFileAs("projects/{$project->slug}/images/thumbnail", $file, $fileName);
            if ($uploadedPath) {
                $fileCreated = File::create([
                    'type'      =>  File::IMAGE,
                    'sub_type'  =>  File::THUMBNAIL,
                    'name'      =>  $fileName,
                    'path'      =>  $uploadedPath,
                ]);
                $project->files()->attach($fileCreated);
            }
        }
    }
}
