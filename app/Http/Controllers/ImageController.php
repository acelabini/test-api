<?php

namespace App\Http\Controllers;

use App\Traits\WithCacheService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageController extends ApiController
{
    use WithCacheService;

    protected const TAG = 'images';

    public function projects(string $slug, string $type, string $subType, string $file)
    {
        $path = "projects/{$slug}/{$type}/{$subType}/{$file}";

        throw_unless(Storage::exists($path), new NotFoundHttpException());

        return response()->make(Storage::get($path), headers: ['Content-Type' => Storage::mimeType($path)]);
    }
}
