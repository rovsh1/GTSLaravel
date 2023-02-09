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
            && Request::has($this->form->getName());
    }

    public function submit(): bool
    {
        if (!$this->isSent()) {
            return false;
        }

        $form = $this->form;
        $form->setSubmitted(true);
        $sentData = Request::input($form->getName());
        $validatedData = $form->getValidator()->validate($sentData);

        $this->setElementsData($sentData);

        return $form->isValid();
    }

    private function setElementsData($validatedData): void
    {
        foreach ($this->form->getElements() as $element) {
            if ($element->disabled || !$element->isSubmittable()) {
                continue;
            }

            if ($element->isFileUpload()) {
                $this->setElementUpload($element);
            } elseif (array_key_exists($element->name, $validatedData)) {
                $element->setValue($validatedData[$element->name]);
            } else {
                $element->setValue(null);
            }
        }
    }

    private function setElementUpload($element): void
    {
        //Request::file();
        if (isset($uploadData[$element->name])) {
            $element->setValue($uploadData[$element->name]);
        }

        if (isset($sentData[$element->name])) {
            $element->setData($sentData[$element->name]);
        }
    }
}
