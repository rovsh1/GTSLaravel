<?php

namespace GTS\Administrator\UI\Admin\Http\Controllers;

use Illuminate\Http\Request;

use GTS\Shared\UI\Common\Http\Controllers\Controller;
use GTS\Administrator\UI\Admin\Http\Actions\Currency as Actions;

class CurrencyController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        return app(Actions\SearchAction::class)->handle($request->input());
    }

    // Todo тут скорее всего в экшн
    public function create()
    {
        return app('layout')
            ->title('Добавление валюты')
            ->view('currency.create');
    }

    // Todo тут скорее всего в экшн
    public function store(Request $request)
    {
        dd($request);
    }

    // Todo тут скорее всего в экшн
    public function edit(\GTS\Administrator\Infrastructure\Models\Currency $currency)
    {
        return app('layout')
            ->title('Редактирование валюты')
            ->view('currency.edit', compact('currency'));
    }

    // Todo тут скорее всего в экшн
    public function update(Request $request, \GTS\Administrator\Infrastructure\Models\Currency $currency)
    {
        dd($request, $currency);
    }

    // Todo тут скорее всего в экшн
    public function destroy(\GTS\Administrator\Infrastructure\Models\Currency $currency)
    {
        dd($currency);
    }
}
