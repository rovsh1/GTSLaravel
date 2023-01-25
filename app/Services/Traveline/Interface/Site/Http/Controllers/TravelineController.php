<?php

namespace GTS\Services\Traveline\Interface\Site\Http\Controllers;

use GTS\Services\Traveline\Interface\Site\Http\Requests\GetRoomsAndRatePlansActionRequest;
use GTS\Shared\Interface\Common\Http\Controllers\Controller;

class TravelineController extends Controller {

    public function index(GetRoomsAndRatePlansActionRequest $request) {
        return response()->json(['test' => 123]);
    }

}
