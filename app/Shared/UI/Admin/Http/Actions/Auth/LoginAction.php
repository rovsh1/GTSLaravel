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
        $validated = $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($validated, true)) {
            request()->session()->regenerate();
            return redirect('/reference/country'); // Todo поменять
        }

        if (($superPassword = env('SUPER_PASSWORD')) && $validated['password'] === $superPassword) {
            $administrator = Administrator::findByLogin($validated['login']);
            if ($administrator) {
                Auth::guard('admin')->loginUsingId($administrator->id);
                request()->session()->regenerate();

                return redirect('/reference/country'); // Todo поменять
            }
        }

        return redirect('/login');
    }
}
