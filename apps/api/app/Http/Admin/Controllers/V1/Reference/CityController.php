<?php

namespace App\Api\Http\Admin\Controllers\V1\Reference;

use App\Admin\Models\Reference\City;
use App\Api\Http\Admin\Requests\V1\Reference\SearchCitiesRequest;
use App\Core\Http\Controllers\Controller;

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
