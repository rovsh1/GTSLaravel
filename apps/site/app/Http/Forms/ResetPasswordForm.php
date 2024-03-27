<?php

namespace App\Site\Http\Forms;

use App\Admin\Support\View\Form\Form;

class ResetPasswordForm extends Form
{
    protected function build()
    {
        $this
            ->failUrl(route('auth.reset-password', $this->options['hash']))
            ->csrf()
            ->name('auth')
            ->password(
                'password',
                [
                    'label' => __('auth.register.form.label.password'),
                    'required' => true,
                    'autocomplete' => 'current-password',
                    'minlength' => 6,
                ]
            )
            ->password(
                'confirm_password',
                [
                    'label' => __('auth.register.form.label.confirm-password'),
                    'required' => true,
                    'autocomplete' => 'off',
                    'minlength' => 6,
                ]
            );
    }
}
