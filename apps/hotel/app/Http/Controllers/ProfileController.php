<?php

namespace App\Hotel\Http\Controllers;

use App\Hotel\Services\Auth\LogoutService;
use App\Hotel\Support\Http\AbstractController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileController extends AbstractController
{
    public function index(): RedirectResponse
    {
        return response()->redirect('home');
    }

    public function logout(Request $request)
    {
        (new LogoutService())->logout($request);

        return redirect(route('auth.login'));
    }
}
