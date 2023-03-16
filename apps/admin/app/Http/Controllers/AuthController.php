<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Actions\Auth as Actions;
use App\Admin\Http\Forms\LoginForm;
use App\Admin\Support\Facades\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index()
    {
        return Layout::style('auth')
            ->view('auth.login', ['form' => new LoginForm('data')]);
    }

    public function login(Request $request)
    {
        return app(Actions\LoginAction::class)->handle($request);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('auth.login'));
    }
}
