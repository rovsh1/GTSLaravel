<?php

namespace App\Admin\Http\Controllers\Reference;

use App\Admin\Http\Requests\City\SearchRequest;
use App\Admin\Models\Reference\City;
use App\Core\Http\Controllers\Controller;

class CityController extends Controller
{
    public function search(SearchRequest $request)
    {
        $query = City::query();
        if ($request->getCountryId() !== null) {
            $query->whereCountryId($request->getCountryId());
        }
        return response()->json($query->get());
    }
}
