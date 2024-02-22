<?php

namespace App\Hotel\Http\Forms\Auth;

use App\Hotel\Services\Auth\RecoveryService;
use App\Hotel\Support\View\Form\FormBuilder;

class RecoveryForm extends FormBuilder
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
            ]);
    }

    public function submit(): bool
    {
        if (!parent::submit()) {
            return false;
        }

        return (new RecoveryService())->handle($this->getData()['login']);
    }
}