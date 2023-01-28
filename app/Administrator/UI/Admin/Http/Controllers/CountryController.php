<?php

namespace GTS\Administrator\UI\Admin\Http\Controllers;

use GTS\Administrator\UI\Admin\Http\Actions\Country as Actions;

class CountryController extends Controller
{
    public function __construct() {}

    public function index()
    {
        return app(Actions\SearchAction::class)->handle();
    }
}
