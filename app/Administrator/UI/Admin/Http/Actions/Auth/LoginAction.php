<?php

namespace GTS\Administrator\UI\Admin\Http\Actions\Auth;

use Illuminate\Http\Request;

use GTS\Administrator\Infrastructure\Facade\AuthFacadeInterface;

class LoginAction
{
    public function __construct(
        public readonly AuthFacadeInterface $authFacade
    ) {}

    public function handle(Request $request)
    {
        $validated = $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $administratorDto = $this->authFacade->login($validated['login'], $validated['password']);

        if (!empty($administratorDto)) {
            return redirect(route('currency.index'));
        }

        return redirect(route('auth.login'));
    }
}
