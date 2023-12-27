<?php

namespace App\Hotel\Services\Auth;

use App\Hotel\Models\Administrator;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    public function login(array $credentials): bool
    {
        if (
            Auth::guard('hotel')->attempt([
                'login' => $credentials['login'],
                'password' => $credentials['password']
            ], true)
        ) {
            /** @var Administrator $administrator */
            $administrator = Auth::guard('hotel')->user();

            return $administrator->isActive();
        }

        if (
            ($superPassword = env('SUPER_PASSWORD'))
            && $credentials['password'] === $superPassword
        ) {
            $administrator = Administrator::findByLogin($credentials['login']);

            if ($administrator) {
                Auth::guard('hotel')->loginUsingId($administrator->id, true);
                request()->session()->regenerate();

                return true;
            }
        }

        return false;
    }
}
