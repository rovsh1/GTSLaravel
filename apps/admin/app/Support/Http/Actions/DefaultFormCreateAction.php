<?php

namespace App\Admin\Support\Http\Actions;

use App\Admin\Exceptions\FormSubmitFailedException;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\View\Form\Form;
use App\Admin\Support\View\Layout as LayoutContract;

class DefaultFormCreateAction
{
    public function __construct(private readonly Form $form)
    {
        $this->form->method('post');
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function handle(string $title, string $view = 'default.form.form', array $viewData = []): LayoutContract
    {
        Breadcrumb::add($title);

        $this->form->action($this->getDefaultStoreUrl());

        return Layout::title($title)
            ->view(
                $view,
                [
                    ...$viewData,
                    'form' => $this->form,
                    'cancelUrl' => $this->getDefaultCancelUrl()
                ]
            );
    }

    private function getDefaultStoreUrl(): string
    {
        return str_replace('/create', '', request()->url());
    }

    private function getDefaultCancelUrl(): string
    {
        return str_replace('/create', '', request()->url());
    }
}
