<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\ProjectProperty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

class ProjectPropertySeeder extends Seeder
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
        /** @var ProjectProperty $projectProperty */
        foreach (ProjectProperty::all() as $projectProperty) {
            /** @var SplFileInfo $file */
            $file = Arr::random($files);
            $fileName = $projectProperty->title . "-" . Str::random(6) . ".{$file->getExtension()}";
            $uploadedPath = Storage::putFileAs("projectProperties/{$projectProperty->title}/images/thumbnail", $file, $fileName);
            if ($uploadedPath) {
                $fileCreated = File::create([
                    'type'      =>  File::IMAGE,
                    'sub_type'  =>  File::THUMBNAIL,
                    'name'      =>  $fileName,
                    'path'      =>  $uploadedPath,
                ]);
                $projectProperty->files()->attach($fileCreated);
            }
        }
    }
}
