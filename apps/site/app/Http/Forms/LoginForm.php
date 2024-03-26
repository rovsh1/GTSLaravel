<?php

namespace App\Site\Http\Forms;

use App\Admin\Support\View\Form\Form;

class LoginForm extends Form
{
    protected function build()
    {
        $this
            ->csrf()
            ->name('auth')
            ->text('login', ['label' => __('auth.login.form.label.login'), 'required' => true, 'autocomplete' => 'username'])
            ->password('password', ['label' => __('auth.login.form.label.password'), 'required' => true, 'autocomplete' => 'current-password']);
    }
}
