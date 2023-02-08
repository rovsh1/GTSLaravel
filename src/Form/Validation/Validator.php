<?php

namespace Gsdk\Form\Validation;

use Illuminate\Support\Facades\Validator as ValidatorFactory;

use Gsdk\Form\Form;

class Validator
{
    private array $rules = [];

    private array $messages = [];

    private array $errors = [];

    public function __construct(private readonly Form $form) {}

    public function validate(array $data): array
    {
        $validator = ValidatorFactory::make($data, $this->buildRules(), $this->buildMessages());

        if ($validator->fails()) {
            $messages = $validator->messages();
            foreach ($messages->keys() as $key) {
                $element = $this->form->getElement($key);
                $message = $this->formatMessage($messages->get($key));
                if ($element) {
                    $element->setError($message);
                } else {
                    $this->addError($message);
                }
            }
        }

        return $validator->valid();
    }

    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }

    public function getErrors(bool $withElementsErrors = true): array
    {
        if (!$withElementsErrors) {
            return $this->errors;
        }

        $errors = $this->errors;

        foreach ($this->form->getElements() as $element) {
            if ($element->hasError()) {
                $errors[$element->name] = $element->getError();
            }
        }

        return $errors;
    }

    public function addError($error): void
    {
        $this->errors[] = $error;
    }

    public function isValid(): bool
    {
        if (!empty($this->errors)) {
            return false;
        }

        foreach ($this->form->getElements() as $element) {
            if ($element->hasError()) {
                return false;
            }
        }

        return true;
    }

    public function reset(): void
    {
        $this->errors = [];
    }

    private function buildMessages(): array
    {
        $messages = $this->messages;
        return $messages;
    }

    private function buildRules(): array
    {
        $rules = $this->rules;

        foreach ($this->form->getElements() as $element) {
            if (!isset($rules[$element->name])) {
                $rules[$element->name] = $element->rules($element);
            }
        }

        return $rules;
    }

    private function formatMessage($message): string
    {
        return $message[0];
    }


}
