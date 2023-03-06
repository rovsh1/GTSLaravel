<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Actions\Auth as Actions;
use App\Admin\Http\Forms\LoginForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return app('layout')
            ->style('auth')
            ->view('auth.login', ['form' => new LoginForm('data')]);
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
