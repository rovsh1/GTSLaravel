<?php

namespace App\Admin\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        return app(\App\Admin\Http\Actions\Currency\SearchAction::class)->handle($request->input());
    }

    public function create()
    {
        return app(\App\Admin\Http\Actions\Currency\CreateAction::class)->handle();
    }

    public function store(Request $request)
    {
        return app(\App\Admin\Http\Actions\Currency\StoreAction::class)->handle($request);
    }

    public function edit(int $id)
    {
        return app(\App\Admin\Http\Actions\Currency\EditAction::class)->handle($id);
    }

    public function update(Request $request, int $id)
    {
        return app(\App\Admin\Http\Actions\Currency\UpdateAction::class)->handle($id, $request);
    }

    public function destroy(int $id)
    {
        return app(\App\Admin\Http\Actions\Currency\DeleteAction::class)->handle($id);
    }
}
