<?php

namespace GTS\Shared\UI\Admin\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use GTS\Shared\UI\Admin\Http\Actions\Auth as Actions;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return (new Actions\LoginAction())->handle($request);
    }

    public function logout()
    {
        Auth::logout();

        return self::redirect('/login');
    }
}
