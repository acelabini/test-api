<?php

namespace App\Services;

use Illuminate\Http\Request;

class LeadService
{
    public static function getSource(Request $request): string
    {
        if ($request->has('fbclid')) {

        }
    }
}
