<?php

namespace Gsdk\Form;

use Gsdk\Form\Validation\Validator;

class Form implements ElementsParentInterface
{
    use Concerns\HasOptions;
    use Concerns\HasElements;
    use Concerns\HasExtensions;

    protected Support\HttpAction $httpAction;

    protected Validator $validator;

    protected array $options = [
        'id' => 'form_data',
        'name' => null,
        'method' => 'post'
    ];

    protected bool $submitted = false;

    public function __call(string $name, array $arguments)
    {
        if (!isset($arguments[0])) {
            throw new \ArgumentCountError('Name required');
        }

        return $this->addElement($arguments[0], $name, $arguments[1] ?? []);
    }

    public function __construct($options = null)
    {
        $this->httpAction = new Support\HttpAction($this);
        $this->validator = new Validator($this);
        $this->validator->setMessages(self::$defaultMessages);

        if (is_string($options)) {
            $options = ['name' => $options];
        }

        if (is_array($options)) {
            if (!isset($options['id']) && isset($options['name'])) {
                $options['id'] = 'form_' . $options['name'];
            }

            $this->mergeOptions($options);
        }

        $this->boot();
    }

    protected function boot() {}

    public function __get($name)
    {
        return $this->options[$name] ?? null;
    }

    public function data($data): static
    {
        $this->getFormData()->add($data);
        return $this;
    }

    public function model($model): static
    {
        $this->getFormData()->setModel($model);
        return $this;
    }

    public function csrf(bool $flag = true): static
    {
        return $this->setOption('csrf', $flag);
    }

    public function view(string $view): static
    {
        return $this->setOption('view', $view);
    }

    public function method(string $method): static
    {
        return $this->setOption('method', $method);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getValidator(): Validator
    {
        return $this->validator;
    }

    public function getFormData(): FormData
    {
        return new FormData($this);
    }

    public function setSubmitted($flag): static
    {
        $this->submitted = (bool)$flag;
        return $this;
    }

    public function isSubmitted(): bool
    {
        return $this->submitted;
    }

    public function isSent(): bool
    {
        return $this->httpAction->isSent();
    }

    public function isValid(): bool
    {
        return $this->validator->isValid();
    }

    public function getData(): array
    {
        return $this->getFormData()->toArray();
    }

    public function getValidData(): array
    {
        return $this->getFormData()->valid();
    }

    public function error(string|array $error): static
    {
        $this->validator->addError($error);

        return $this;
    }

    public function errors(bool $withElementsErrors = true): array
    {
        return $this->validator->getErrors($withElementsErrors);
    }

    public function rules(array $rules): static
    {
        $this->validator->setRules($rules);
        return $this;
    }

    public function messages(array $messages): static
    {
        $this->validator->setMessages($messages);
        return $this;
    }

    public function reset(): void
    {
        foreach ($this->elements as $element) {
            $element->reset();
        }

        $this->validator->reset();
        $this->submitted = false;
    }

    public function submit(): bool
    {
        return $this->httpAction->submit();
    }

    public function render(): string
    {
        return (new Renderer\FormRenderer())->render($this);
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
