<?php

namespace App\Hotel\Http\Controllers;

use App\Admin\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return redirect(route('auth.login'));
    }
}
