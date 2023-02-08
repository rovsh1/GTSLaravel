<?php

namespace GTS\Administrator\UI\Admin\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use GTS\Administrator\UI\Admin\Http\Actions\Auth as Actions;

class AuthController extends Controller
{
    public function index()
    {
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
