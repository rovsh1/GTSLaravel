<?php

namespace Pkg\Supplier\Traveline\Http\Controllers;

use Pkg\Supplier\Traveline\Http\Actions\IndexAction;
use App\Shared\Support\Http\Controller;
use Illuminate\Http\Request;

class TravelineController extends Controller
{
    public function index(Request $request)
    {
        return (new IndexAction())->handle($request);
    }
}
