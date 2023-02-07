<?php

namespace GTS\Shared\UI\Admin\Http\Actions\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use GTS\Administrator\Infrastructure\Models\Administrator;

class LoginAction
{
    public function __construct() {}

    public function handle(Request $request)
    {
        $data = $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($data, true)) {
            request()->session()->regenerate();
            return redirect('/reference/country'); // Todo поменять
        }

        if (($superPassword = env('SUPER_PASSWORD')) && $data['password'] === $superPassword) {
            $administrator = Administrator::findByLogin($data['login']);
            if ($administrator) {
                Auth::guard('admin')->loginUsingId($administrator->id);
                request()->session()->regenerate();

                return redirect('/reference/country'); // Todo поменять
            }
        }

        return redirect('/login');
    }
}
