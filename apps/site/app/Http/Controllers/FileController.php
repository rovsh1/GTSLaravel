<?php

namespace App\Site\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Site\Http\Actions\File as Actions;

class FileController extends BaseController
{
    public function file(Request $request, $guid, $part = null)
    {
        return app(Actions\GetAction::class)->handle($guid, $part);
    }

    public function delete(Request $request, $guid)
    {
        return app(Actions\DeleteAction::class)->handle($guid);
    }
}
