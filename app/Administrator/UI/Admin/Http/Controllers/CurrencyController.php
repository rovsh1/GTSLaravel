<?php

namespace GTS\Administrator\UI\Admin\Http\Controllers;

use GTS\Shared\UI\Common\Http\Controllers\Controller;

use GTS\Administrator\UI\Admin\Http\Actions\Currency as Actions;

class CurrencyController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        return app(Actions\SearchAction::class)->handle();
    }
}
