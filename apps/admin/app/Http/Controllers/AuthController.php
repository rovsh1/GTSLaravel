<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Actions\Auth as Actions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        // Todo переделать после изменения вебпака
        app('layout')->head->addStyle(asset('assets/vendor/css/pages/page-auth.css'));

        return app('layout')
            ->layout('layouts.blank')
            ->view('auth.login');
    }

    public function login(Request $request)
    {
        return app(Actions\LoginAction::class)->handle($request);
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('auth.login'));
    }
}
