<?php

namespace GTS\Administrator\UI\Admin\Http\Controllers;

use Illuminate\Http\Request;

use GTS\Administrator\UI\Admin\Http\Actions\Test as Actions;

class TestController extends Controller
{
    public function __construct() {}

    public function form(Request $request)
    {
        return app(Actions\FormAction::class)->handle();
    }
}
