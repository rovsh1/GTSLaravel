<?php

namespace App\Admin\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function __construct() {}

    public function form(Request $request)
    {
        return app(\App\Admin\Http\Actions\Test\FormAction::class)->handle();
    }
}
