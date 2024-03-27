<?php

namespace App\Site\Http\Controllers;

use Illuminate\Contracts\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        return view('profile.profile');
    }
}
