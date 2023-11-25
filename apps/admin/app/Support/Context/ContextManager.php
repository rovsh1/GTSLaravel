<?php

namespace App\Admin\Support\Context;

use App\Admin\Support\View\Form\Form;
use Module\Shared\Contracts\Service\ApplicationContextInterface;

class ContextManager
{
    private Form $submittedForm;

    public function __construct(private readonly ApplicationContextInterface $applicationContext) {}

    public function __call(string $name, array $arguments)
    {
        $this->applicationContext->$name(...$arguments);
    }

    public function setSubmittedForm(Form $form): void
    {
        $this->submittedForm = $form;
    }

    public function submittedForm(): ?Form
    {
        return $this->submittedForm ?? null;
    }

    public function toArray(): array
    {
        $data = $this->applicationContext->toArray();
        if (isset($this->submittedForm)) {
            $data['formData'] = $this->submittedForm->getData();
        }

        return $data;
    }
}