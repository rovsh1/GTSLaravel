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

    public function edit(int $id)
    {
        return app(Actions\EditAction::class)->handle($id);
    }

    public function update(Request $request, int $id)
    {
        return app(Actions\UpdateAction::class)->handle($id, $request);
    }

    public function destroy(int $id)
    {
        return app(Actions\DeleteAction::class)->handle($id);
    }
}
