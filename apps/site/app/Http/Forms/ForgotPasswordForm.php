<?php

namespace App\Site\Http\Forms;

use App\Admin\Support\View\Form\Form;

class ForgotPasswordForm extends Form
{
    protected function build()
    {
        $this
            ->failUrl(route('auth.forgot-password'))
            ->csrf()
            ->name('auth')
            ->email(
                'email',
                ['label' => __('auth.register.form.label.email'), 'required' => true, 'autocomplete' => 'email']
            );
    }
}
