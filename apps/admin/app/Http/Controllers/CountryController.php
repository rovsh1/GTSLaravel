<?php

namespace App\Admin\Http\Controllers;

use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        return app(\App\Admin\Http\Actions\Country\SearchAction::class)->handle($request->input());
    }
}
