<?php

namespace App\Site\Http\Controllers;

use App\Site\Http\Actions\Auth as Actions;
use App\Site\Http\Forms\LoginForm;
use App\Site\Http\Middleware\TryAuthenticate;
use App\Admin\Support\Facades\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(TryAuthenticate::class)->except('logout');
        $this->middleware('guest:site')->except('logout');
    }

    public function index()
    {
        return view('auth.login.login', ['form' => new LoginForm('data')]);
    }

    public function login(Request $request)
    {
        return app(Actions\LoginAction::class)->handle($request);
    }

    public function logout(Request $request)
    {
        Auth::guard('site')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('auth.login'));
    }
}
