<?php

namespace App\Admin\Http\Controllers;

use Illuminate\Http\Request;

use App\Admin\Support\Http\CRUD;
use App\Admin\Http\Requests\City as Requests;
use App\Admin\Http\Actions\City as Actions;
use App\Admin\Http\Forms\City\EditForm;
use App\Admin\Models\City;

class CityController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        return app(Actions\SearchAction::class)->handle($request->input());
    }

    public function create()
    {
        return app('layout')
            ->title('Новый город')
            ->view('reference.city.form', [
                'form' => (new EditForm('data'))
                    ->route(route('city.store'))
            ]);
    }

    public function store(Requests\StoreRequest $request, City $city)
    {
        return app(CRUD\StoreAction::class)->handle($request, $city);
    }

    public function edit(City $city)
    {
        return app('layout')
            ->title($city->name)
            ->view('reference.city.form', [
                'form' => (new EditForm('data'))
                    ->data($city->toArray())
                    ->method('put')
                    ->route(route('city.update', $city))
            ]);
    }

    public function update(Requests\UpdateRequest $request, City $city)
    {
        return app(CRUD\UpdateAction::class)->handle($request, $city);
    }

    public function destroy(Requests\DeleteRequest $request, City $city)
    {
        return app(CRUD\DeleteAction::class)->handle($request, $city);
    }
}
