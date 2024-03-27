<?php

namespace App\Site\Http\Actions\Auth;

use App\Site\Http\Forms\ResetPasswordForm;
use App\Site\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResetPasswordAction
{
    public function __construct() {}

    public function handle(Request $request, string $hash)
    {
        $form = new ResetPasswordForm(['hash' => $hash]);
        if ($form->submit()) {
            $user = User::whereRecoveryHash($hash)->first();
            if ($user === null) {
                $form->error(__('auth.reset-password.form.error.expired'));
                $form->throwError();
            }

            $this->updatePassword($form, $user);
            $request->session()->regenerate();

            $url = route('home');

            return redirect($url);
        }

        return redirect(route('auth.reset-password'))
            ->withErrors($form->errors());
    }

    private function updatePassword(ResetPasswordForm $form, User $user): void
    {
        $data = $form->getData();
        if ($data['password'] !== $data['confirm_password']) {
            $form->error(__('auth.register.form.error.different-password'));
            $form->throwError();
        }

        $user->update([
            'password' => $data['password'],
            'recovery_hash' => null,
        ]);

        Auth::guard('site')->login($user);
    }
}
