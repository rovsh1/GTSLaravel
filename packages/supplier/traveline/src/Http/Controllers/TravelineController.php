<?php

namespace Pkg\Supplier\Traveline\Http\Controllers;

use App\Shared\Support\Http\Controller;
use Illuminate\Http\Request;
use Pkg\Supplier\Traveline\Http\Actions\IndexAction;

class TravelineController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, \Closure $next) {
            module('Traveline')->boot();

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        return (new IndexAction())->handle($request);
    }
}
