<?php

namespace Gsdk\Form\Renderer;

use Gsdk\Form\ElementInterface;
use Gsdk\Form\Form;

class ElementRenderer
{
    public function __construct(private readonly Form $form) {}

    public function render(ElementInterface $element)
    {
        if (!$element->isRenderable() || $element->isRendered()) {
            return '';
        } elseif (($view = $element->view)) {
            return view($view, $this->getViewData($element));
        }

        return $this->renderField($element);
    }

    private function renderField(ElementInterface $element): string
    {
        if ($element->isHidden() && !$element->label) {
            return $element->render();
        }

        return Compiler::compile('field', $this->getViewData($element));
    }

    private function getViewData($element): array
    {
        $error = null;
        $cls = 'form-field field-' . $element->type;
        if ($element->name !== $element->type) {
            $cls .= ' field-' . $element->name;
        }

        if ($element->hasError()) {
            $cls .= ' field-invalid';
            $error = $element->getError();
        }

        if ($element->required) {
            $cls .= ' field-required';
        }

        return [
            'element' => $element,
            'label' => $element->getLabel(),
            'hint' => $element->hint,
            'error' => $error,
            'class' => $cls
        ];
    }
}
