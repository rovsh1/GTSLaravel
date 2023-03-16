<?php

namespace Gsdk\Form\Renderer;

use Gsdk\Form\Form;

class FormRenderer
{
    public function render(Form $form): string
    {
        $html = '';

        if ($form->getOption('csrf')) {
            $html .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
        }

        $html .= '<input type="hidden" name="_method" value="' . $form->getOption('method') . '">';

        if (($view = $form->getOption('view'))) {
            $html .= view($view, $this->getViewData($form));
        } else {
            $html .= Compiler::compile('form', $this->getViewData($form));
        }

        return $html;
    }

    private function getViewData(Form $form): array
    {
        return [
            'form' => $form,
            'errors' => new ErrorsRenderer($form),
            'elements' => new ElementsRenderer($form)
        ];
    }
}
