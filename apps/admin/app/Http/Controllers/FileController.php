<?php

namespace App\Admin\Http\Controllers;

use App\Core\Support\Facades\FileAdapter;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function delete(Request $request, string $guid)
    {
        FileAdapter::delete($guid);

        if ($request->expectsJson()) {
            return new AjaxSuccessResponse();
        } else {
            redirect()->back();
        }
    }
}
