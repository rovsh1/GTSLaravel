<?php

declare(strict_types=1);

namespace App\Hotel\Http\Controllers\Reference;

use App\Hotel\Http\Resources\Country as CountryResource;
use App\Hotel\Models\Reference\Country;
use App\Hotel\Support\Http\AbstractController;
use Illuminate\Http\JsonResponse;

class CountriesController extends AbstractController
{
    public function list(): JsonResponse
    {
        return response()->json(
            CountryResource::collection(
                Country::all()
            )
        );
    }
}
