<?php

namespace Gsdk\Form\Support;

use Illuminate\Support\Facades\Request;

use Gsdk\Form\Form;

class HttpAction
{
    public function __construct(private readonly Form $form) {}

    public function isSent(): bool
    {
        return Request::method() === strtoupper($this->form->getOption('method'))
            && (!$this->form->getName() || Request::has($this->form->getName()));
    }

    public function submit(): bool
    {
        if (!$this->isSent()) {
            return false;
        }

        $form = $this->form;
        $form->setSubmitted(true);
        $sentData = Request::input($form->getName()) ?? [];
        $validatedData = $form->getValidator()->validate($sentData);

        $this->setElementsData($sentData);

        return $form->isValid();
    }

    private function setElementsData($sentData): void
    {
        foreach ($this->form->getElements() as $element) {
            if ($element->disabled || !$element->isSubmittable()) {
                continue;
            }

            if ($element->isFileUpload()) {
                $this->setElementUpload($sentData, $element);
            } elseif (array_key_exists($element->name, $sentData)) {
                $element->submitValue($sentData[$element->name]);
            } else {
                $element->submitValue(null);
            }
        }
    }

    private function setElementUpload($sentData, $element): void
    {
        $uploadedFile = Request::file($this->form->name . '.' . $element->name);
        if ($uploadedFile) {
            $element->submitValue($uploadedFile);
        } elseif (isset($sentData[$element->name])) {
            $element->submitValue($sentData[$element->name]);
        }
    }
}
