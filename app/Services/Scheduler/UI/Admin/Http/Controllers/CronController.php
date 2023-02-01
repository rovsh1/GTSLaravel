<?php

namespace GTS\Services\Scheduler\UI\Admin\Http\Controllers;

use Illuminate\Http\Request;

use GTS\Services\Scheduler\UI\Admin\Http\Actions\Cron as Actions;

class CronController extends Controller
{
    public function index(Request $request)
    {
        return app(Actions\IndexAction::class)->handle($request->query());
    }

    public function edit(Request $request, $id)
    {
        return app(Actions\EditAction::class)->handle($id);
    }

    public function create(Request $request)
    {
        return app(Actions\EditAction::class)->handle();
    }

    public function data(Request $request, $id)
    {
        return app(Actions\DataAction::class)->handle($id);
    }

    public function run(Request $request, $id)
    {
        return app(Actions\RunAction::class)->handle($id);
    }

    public function delete(Request $request, $id)
    {
        return app(Actions\DeleteAction::class)->handle($id);
    }

}
