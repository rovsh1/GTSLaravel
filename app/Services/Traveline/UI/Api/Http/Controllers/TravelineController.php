<?php

namespace GTS\Services\Traveline\UI\Api\Http\Controllers;

use Illuminate\Http\Request;

use GTS\Services\Traveline\UI\Api\Http\Actions\IndexAction;
use GTS\Shared\UI\Common\Http\Controllers\Controller;

class TravelineController extends Controller
{

    public function index(Request $request)
    {
        return (new IndexAction())->handle($request);
    }

}
