<?php

namespace App\Admin\Support\Http\Actions;

use App\Admin\Exceptions\FormSubmitFailedException;
use App\Admin\Support\View\Form\Form;
use Illuminate\Http\RedirectResponse;

class DefaultFormStoreAction
{
    public function __construct(private readonly Form $form)
    {
        $this->form->method('post');
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function handle(string $model): RedirectResponse
    {
        $this->form->trySubmit($this->getDefaultFailUrl());

        $model::create($this->form->getData());

        return redirect($this->getDefaultSuccessUrl());
    }

    private function getDefaultFailUrl(): string
    {
        return request()->url() . '/create';
    }

    private function getDefaultSuccessUrl(): string
    {
        return request()->url();
    }
}
