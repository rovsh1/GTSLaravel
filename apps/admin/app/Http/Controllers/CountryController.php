<?php

namespace App\Admin\Http\Controllers;

use Illuminate\Http\Request;

use App\Admin\Support\Http\CRUD;
use App\Admin\Http\Requests\Country as Requests;
use App\Admin\Http\Actions\Country as Actions;
use App\Admin\Http\Forms\Country\EditForm;
use App\Admin\Models\Country;

class CountryController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        return app(Actions\SearchAction::class)->handle($request->input());
    }

    public function create()
    {
        return app('layout')
            ->title('Новая страна')
            ->view('reference.country.form', [
                'form' => (new EditForm('data'))
                    ->route(route('country.store'))
            ]);
    }

    public function store(Requests\StoreRequest $request, Country $country)
    {
        return app(CRUD\StoreAction::class)->handle($request, $country);
    }

    public function edit(Country $country)
    {
        return app('layout')
            ->title($country->name)
            ->view('reference.country.form', [
                'form' => (new EditForm('data'))
                    ->data($country->toArray())
                    ->method('put')
                    ->route(route('country.update', $country))
            ]);
    }

    public function update(Requests\UpdateRequest $request, Country $country)
    {
        return app(CRUD\UpdateAction::class)->handle($request, $country);
    }

    public function destroy(Requests\DeleteRequest $request, Country $country)
    {
        return app(CRUD\DeleteAction::class)->handle($request, $country);
    }
}
