<?php

namespace App\Admin\Http\Forms;

use App\Admin\Support\View\Form\Form;

class LoginForm extends Form
{
    protected function build()
    {
        $this
            ->csrf()
            ->name('auth')
            ->text('login', ['label' => 'Логин', 'required' => true, 'autocomplete' => 'username'])
            ->password('password', ['label' => 'Пароль', 'required' => true, 'autocomplete' => 'current-password']);
    }
}
