<?php

namespace Gsdk\Form\Renderer;

use Gsdk\Form\Form;

class ErrorsRenderer
{
    public function __construct(private readonly Form $form) {}

    public function render(string $view = null): string
    {
        if ($this->form->isValid()) {
            return '';
        } elseif ($view) {
            return view($view, $this->getViewData());
        } else {
            return Compiler::compile('errors', $this->getViewData());
        }
    }

    public function __toString(): string
    {
        return $this->render();
    }

    private function getViewData(): array
    {
        return [
            'errors' => $this->form->errors(false)
        ];
    }
}
