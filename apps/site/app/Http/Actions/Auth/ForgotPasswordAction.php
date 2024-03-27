<?php

namespace App\Site\Http\Actions\Auth;

use App\Site\Http\Forms\ForgotPasswordForm;
use App\Site\Models\User;
use Illuminate\Http\Request;

class ForgotPasswordAction
{
    public function __construct() {}

    public function handle(Request $request)
    {
        $form = new ForgotPasswordForm();
        if ($form->submit() && $this->sendResetPasswordMail($form)) {
            $url = route('auth.forgot-password.success');
            return redirect($url);
        }

        return redirect(route('auth.forgot-password'))
            ->withErrors($form->errors());
    }

    private function sendResetPasswordMail(ForgotPasswordForm $form): bool
    {
        $email = $form->getData()['email'];

        $user = User::whereEmail($email)->first();
        if ($user === null) {
            $form->error(__('auth.forgot-password.form.error.not-registered'));
            $form->throwError();
        }

        if (!empty($user->recovery_hash)) {
            $form->error(__('auth.forgot-password.form.error.limit-exceeded'));
            $form->throwError();
        }

        $linkExpireTime = strtotime('+15 minutes');
        $recoveryHash = hash('sha256', $email . '_' . time() . '_' . $linkExpireTime);
        $user->update(['recovery_hash' => $recoveryHash]);
        $jobDelay = $linkExpireTime - time();

        //@todo может как то по другому очищать это поле?
        dispatch(function () use ($recoveryHash, $email) {
            User::whereEmail($email)->whereRecoveryHash($recoveryHash)->update(['recovery_hash' => null]);
        })->delay($jobDelay);

        $resetPasswordUrl = route('auth.reset-password', ['hash' => $recoveryHash]);
        \Log::debug('reset link: ' . $resetPasswordUrl);
        //@todo send mail with link

        return true;
    }
}
