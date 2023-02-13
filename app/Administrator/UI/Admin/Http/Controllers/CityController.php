<?php

namespace GTS\Administrator\UI\Admin\Http\Controllers;

use Illuminate\Http\Request;

use GTS\Shared\UI\Common\Http\Controllers\Controller;
use GTS\Administrator\UI\Admin\Http\Actions\City as Actions;

class CityController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        return app(Actions\SearchAction::class)->handle($request->input());
    }

    // Todo тут скорее всего в экшн
    public function create()
    {
        return app(Actions\CreateAction::class)->handle();
    }

    // Todo тут скорее всего в экшн
    public function store(Request $request)
    {
        dd($request);
    }

    // Todo тут скорее всего в экшн
    public function edit(\GTS\Administrator\Infrastructure\Models\City $city)
    {
        return app(Actions\EditAction::class)->handle($city->toArray());
    }

    // Todo тут скорее всего в экшн
    public function update(Request $request, \GTS\Administrator\Infrastructure\Models\City $city)
    {
        dd($request, $city);
    }

    // Todo тут скорее всего в экшн
    public function destroy(\GTS\Administrator\Infrastructure\Models\City $city)
    {
        dd($city);
    }
}
