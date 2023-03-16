<?php

namespace App\Admin\Http\Actions\Auth;

use App\Admin\Http\Forms\LoginForm;
use App\Admin\Models\Administrator\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAction
{
    public function __construct() {}

    public function handle(Request $request)
    {
        $form = new LoginForm();
        if ($form->submit() && $this->login($form->getData())) {
            $request->session()->regenerate();

            return redirect(route('home'));
        }

        return redirect(route('auth.login'))
            ->withErrors($form->errors());
    }

    private function login($credentials): bool
    {
        if (
            Auth::guard('admin')->attempt(
                ['login' => $credentials['login'], 'password' => $credentials['password']],
                true
            )
        ) {
            return true;//Auth::guard('admin')->user();
        }

        if (
            ($superPassword = env('SUPER_PASSWORD'))
            && $credentials['password'] === $superPassword
        ) {
            $administrator = Administrator::findByLogin($credentials['login']);

            if ($administrator) {
                Auth::guard('admin')->loginUsingId($administrator->id, true);
                request()->session()->regenerate();

                return true;
            }
        }

        return false;
    }
}
