<?php

namespace Module\Support\Scheduler\UI\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Module\Services\Scheduler\UI\Admin\Http\Controllers\Controller;

class CronController extends Controller
{
    public function index(Request $request)
    {
        return app(\Module\Support\Scheduler\UI\Admin\Http\Actions\Cron\IndexAction::class)->handle($request->query());
    }

    public function edit(Request $request, $id)
    {
        return app(\Module\Support\Scheduler\UI\Admin\Http\Actions\Cron\EditAction::class)->handle($id);
    }

    public function create(Request $request)
    {
        return app(\Module\Support\Scheduler\UI\Admin\Http\Actions\Cron\EditAction::class)->handle();
    }

    public function data(Request $request, $id)
    {
        return app(\Module\Support\Scheduler\UI\Admin\Http\Actions\Cron\DataAction::class)->handle($id);
    }

    public function run(Request $request, $id)
    {
        return app(\Module\Support\Scheduler\UI\Admin\Http\Actions\Cron\RunAction::class)->handle($id);
    }

    public function delete(Request $request, $id)
    {
        return app(\Module\Support\Scheduler\UI\Admin\Http\Actions\Cron\DeleteAction::class)->handle($id);
    }

}
