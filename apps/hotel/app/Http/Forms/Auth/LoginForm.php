<?php

namespace App\Hotel\Http\Forms\Auth;

use App\Hotel\Services\Auth\LoginService;
use App\Hotel\Support\View\Form\FormBuilder;

class LoginForm extends FormBuilder
{
    protected function build(): void
    {
        $this
            ->name('auth')
            ->method('post')
            ->csrf()
            ->text('login', [
                'label' => __('auth.login.form.label.login'),
                'required' => true,
                'autocomplete' => 'username'
            ])
            ->password('password', [
                'label' => __('auth.login.form.label.password'),
                'required' => true,
                'autocomplete' => 'current-password'
            ]);
    }

    public function submit(): bool
    {
        if (!parent::submit()) {
            return false;
        }

        return (new LoginService())->login($this->getData());
    }
}