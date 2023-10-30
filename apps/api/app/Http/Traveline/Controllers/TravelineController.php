<?php

namespace App\Api\Http\Traveline\Controllers;

use App\Api\Http\Traveline\Actions\IndexAction;
use App\Shared\Support\Http\Controller;
use Illuminate\Http\Request;

class TravelineController extends Controller
{
    public function index(Request $request)
    {
        return (new IndexAction())->handle($request);
    }
}
