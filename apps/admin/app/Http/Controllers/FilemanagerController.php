<?php

namespace App\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Gsdk\Filemanager\Http\Controllers\FilemanagerController as Controller;

class FilemanagerController extends Controller
{
    public function index(Request $request)
    {
        return app('layout')
            ->title('FileManager')
            ->ss('filemanager/index')
            ->view('filemanager');
    }
}
