<?php

namespace Module\Integration\Traveline\UI\Api\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\Integration\Traveline\UI\Api\Http\Actions\IndexAction;

class TravelineController extends Controller
{

    public function index(Request $request)
    {
        return (new IndexAction())->handle($request);
    }

}
