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

    public function create()
    {
        return app(Actions\CreateAction::class)->handle();
    }

    public function store(Request $request)
    {
        return app(Actions\StoreAction::class)->handle($request);
    }

    // Todo тут скорее всего в экшн
    public function edit(int $id)
    {
        return app(Actions\EditAction::class)->handle($id);
    }

    // Todo тут скорее всего в экшн
    public function update(Request $request, \GTS\Administrator\Infrastructure\Models\Currency $currency)
    {
        dd('update', $request, $currency);
    }

    // Todo тут скорее всего в экшн
    public function destroy(\GTS\Administrator\Infrastructure\Models\Currency $currency)
    {
        dd($currency);
    }
}
