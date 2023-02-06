<?php

namespace GTS\Services\Integration\Traveline\UI\Api\Http\Controllers;

use GTS\Services\Integration\Traveline\UI\Api\Http\Actions\IndexAction;
use GTS\Shared\UI\Common\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TravelineController extends Controller
{

    public function index(Request $request)
    {
        return (new IndexAction())->handle($request);
    }

}
