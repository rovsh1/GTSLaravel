<?php

namespace Module\Services\FileStorage\UI\Site\Http\Controllers;

use GTS\Services\FileStorage\UI\Site\Http\Actions;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class FileController extends BaseController
{
    public function file(Request $request, $guid, $part = null)
    {
        return app(\Module\Services\FileStorage\UI\Site\Http\Actions\GetAction::class)->handle($guid, $part);
    }

    public function delete(Request $request, $guid)
    {
        return app(\Module\Services\FileStorage\UI\Site\Http\Actions\DeleteAction::class)->handle($guid);
    }
}
