<?php

namespace App\Api\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        app('portGateway')->request('traveline/update', []);
        return 'ok';
    }
}
