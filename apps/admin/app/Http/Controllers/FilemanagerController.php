<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Support\Facades\Layout;
use Illuminate\Http\Request;
use Gsdk\Filemanager\Http\Controllers\FilemanagerController as Controller;

class FilemanagerController extends Controller
{
    public function index(Request $request)
    {
        return Layout::title('FileManager')
            ->view('file-manager.file-manager')
            ->render();
    }
}
