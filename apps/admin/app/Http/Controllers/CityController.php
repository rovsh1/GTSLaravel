<?php

namespace App\Admin\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        return app(\App\Admin\Http\Actions\City\SearchAction::class)->handle($request->input());
    }

    // Todo тут скорее всего в экшн
    public function create()
    {
        return app(\App\Admin\Http\Actions\City\CreateAction::class)->handle();
    }

    // Todo тут скорее всего в экшн
    public function store(Request $request)
    {
        dd($request);
    }

    // Todo тут скорее всего в экшн
    public function edit(\App\Admin\Models\City $city)
    {
        return app(\App\Admin\Http\Actions\City\EditAction::class)->handle($city->toArray());
    }

    // Todo тут скорее всего в экшн
    public function update(Request $request, \App\Admin\Models\City $city)
    {
        dd($request, $city);
    }

    // Todo тут скорее всего в экшн
    public function destroy(\App\Admin\Models\City $city)
    {
        dd($city);
    }
}
