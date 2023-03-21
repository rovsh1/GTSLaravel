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
            //dd($messages, $validator->getMessageBag());
            $this->setErrors($messages);
        }

        return $validator->valid();
    }

    public function setErrors($errors): void
    {
        foreach ($errors->keys() as $key) {
            $element = $this->form->getElement($key);
            if ($element) {
                $element->setErrors($errors->get($key));
            } else {
                $this->addError($errors->get($key));
            }
        }
    }

    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    public function setMessages(array $messages): void
    {
        $this->messages = array_merge($this->messages, $messages);
    }

    public function getErrors(bool $withElementsErrors = true): array
    {
        if (!$withElementsErrors) {
            return $this->errors;
        }

        $errors = $this->errors;

        foreach ($this->form->getElements() as $element) {
            if ($element->hasError()) {
                $errors[$element->name] = $element->getErrors();
            }
        }

        return $errors;
    }

    public function addError(string|array $error): void
    {
        if (is_string($error)) {
            $this->errors[] = $error;
        } else {
            $this->errors = array_merge($this->errors, $error);
        }
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
                $elementRules = $element->rules($element);
                if (is_array($elementRules) && !isset($elementRules[0])) {
                    $rules = array_merge($rules, $elementRules);
                } else {
                    $rules[$element->name] = $elementRules;
                }
            }
        }

        return $rules;
    }

    private function formatMessage($message): string
    {
        return $message[0];
    }


}
