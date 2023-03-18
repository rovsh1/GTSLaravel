<?php

namespace App\Admin\Components\Factory\Support;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Support\Facades\Layout;

class NotImplementedController extends Controller
{
    public function index()
    {
        return Layout::title('Not implemented')
            ->view('error.not-implemented');
    }
}
