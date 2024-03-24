<?php

namespace App\Site\Http\Actions\Auth;

use App\Site\Http\Forms\LoginForm;
use App\Site\Models\User;
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

            $url = $request->query('url') ?? route('home');

            return redirect($url);
        }

        return redirect(route('auth.login'))
            ->withErrors($form->errors());
    }

    private function login(array $credentials): bool
    {
        if (
            Auth::guard('site')->attempt([
                'login' => $credentials['login'],
                'password' => $credentials['password']
            ], true)
        ) {
            /** @var User $user */
            $user = Auth::guard('site')->user();

            return $user->isActive();
        }

        return false;
    }
}
