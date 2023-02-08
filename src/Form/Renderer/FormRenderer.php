<?php

namespace Gsdk\Form\Renderer;

use Gsdk\Form\Form;

class FormRenderer
{
    public function render(Form $form): string
    {
        if (($view = $form->getOption('view'))) {
            return view($view, $this->getViewData($form));
        } else {
            return Compiler::compile('form', $this->getViewData($form));
        }
    }

    private function getViewData(Form $form): array
    {
        return [
            'form' => $form,
            'csrf' => $form->getOption('csrf') ? '<input type="hidden" name="_token" value="' . csrf_token() . '">' : '',
            'errors' => new ErrorsRenderer($form),
            'elements' => new ElementsRenderer($form)
        ];
    }
}
