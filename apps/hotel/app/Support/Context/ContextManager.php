<?php

namespace App\Hotel\Support\Context;

use App\Hotel\Support\View\Form\FormBuilder;
use Sdk\Shared\Contracts\Service\ApplicationContextInterface;

class ContextManager
{
    private FormBuilder $submittedForm;

    public function __construct(private readonly ApplicationContextInterface $applicationContext)
    {
    }

    public function __call(string $name, array $arguments)
    {
        $this->applicationContext->$name(...$arguments);
    }

    public function setSubmittedForm(FormBuilder $form): void
    {
        $this->submittedForm = $form;
    }

    public function submittedForm(): ?FormBuilder
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
