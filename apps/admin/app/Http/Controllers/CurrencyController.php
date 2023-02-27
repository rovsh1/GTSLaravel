<?php

namespace App\Admin\Http\Controllers;

use Illuminate\Http\Request;

use App\Admin\Support\Http\CRUD;
use App\Admin\Http\Requests\Currency as Requests;
use App\Admin\Http\Actions\Currency as Actions;
use App\Admin\Http\Forms\Currency\EditForm;
use App\Admin\Models\Currency;

class CurrencyController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        return app(Actions\SearchAction::class)->handle($request->input());
    }

    public function create()
    {
        return app('layout')
            ->title('Новая валюта')
            ->view('reference.currency.form', [
                'form' => (new EditForm('data'))
                    ->route(route('currency.store'))
            ]);
    }

    public function store(Requests\StoreRequest $request, Currency $currency)
    {
        return app(CRUD\StoreAction::class)->handle($request, $currency);
    }

    public function edit(Currency $currency)
    {
        return app('layout')
            ->title($currency->name)
            ->view('reference.currency.form', [
                'form' => (new EditForm('data'))
                    ->data($currency->toArray())
                    ->method('put')
                    ->route(route('currency.update', $currency))
            ]);
    }

    public function update(Requests\UpdateRequest $request, Currency $currency)
    {
        return app(CRUD\UpdateAction::class)->handle($request, $currency);
    }

    public function destroy(Requests\DeleteRequest $request, Currency $currency)
    {
        return app(CRUD\DeleteAction::class)->handle($request, $currency);
    }
}
