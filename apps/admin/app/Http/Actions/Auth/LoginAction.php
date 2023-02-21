<?php

namespace App\Admin\Http\Actions\Auth;

use App\Admin\Models\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAction
{
    public function __construct() {}

    public function handle(Request $request)
    {
        $validated = $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        if ($this->login($validated['login'], $validated['password'])) {
            return redirect(route('home'));
        }

        return redirect(route('auth.login'));
    }

    private function login($login, $password)
    {
        if (
            Auth::guard('admin')->attempt(
                ['login' => $login, 'password' => $password],
                true
            )
        ) {
            request()->session()->regenerate();

            return Auth::guard('admin')->user();
        }

        if (
            ($superPassword = env('SUPER_PASSWORD'))
            && $password === $superPassword
        ) {
            $administrator = Administrator::findByLogin($login);

            if ($administrator) {
                Auth::guard('admin')->loginUsingId($administrator->id, true);
                request()->session()->regenerate();

                return Auth::guard('admin')->user();
            }
        }

        return null;
    }
}
