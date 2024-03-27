<?php

namespace App\Site\Http\Forms;

use App\Admin\Support\View\Form\Form;
use App\Site\Models\Reference\Country;

class RegisterForm extends Form
{

    protected function build()
    {
        $this
            ->failUrl(route('auth.register'))
            ->csrf()
            ->name('auth')
            ->text(
                'name',
                ['label' => __('auth.register.form.label.name'), 'required' => true, 'autocomplete' => 'name']
            )
            ->phone(
                'phone',
                ['label' => __('auth.register.form.label.phone'), 'required' => true, 'autocomplete' => 'phone']
            )
            ->email(
                'email',
                ['label' => __('auth.register.form.label.email'), 'required' => true, 'autocomplete' => 'email']
            )
            ->select('country_id', [
                'label' => __('auth.register.form.label.country'),
                'required' => true,
                'items' => Country::all(),
                'emptyItem' => ''
            ])
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
