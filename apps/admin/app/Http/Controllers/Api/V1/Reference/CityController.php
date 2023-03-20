<?php

namespace App\Admin\Http\Controllers\Api\V1\Reference;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Api\V1\Reference\SearchCitiesRequest;
use App\Admin\Models\Reference\City;

class CityController extends Controller
{
    public function search(SearchCitiesRequest $request)
    {
        $query = City::query();
        if ($request->getCountryId() !== null) {
            $query->where('country_id', $request->getCountryId());
        }
        return response()->json($query->get());
    }
}
