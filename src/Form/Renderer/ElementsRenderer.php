<?php

namespace Gsdk\Form\Renderer;

use Gsdk\Form\Form;

class ElementsRenderer
{
    public function __construct(private readonly Form $form) {}

    public function render(...$keys): string
    {
        $html = '';
        if (empty($keys)) {
            foreach ($this->form->getElements() as $element) {
                $html .= (new ElementRenderer($this->form))->render($element);
            }
        } else {
            foreach ($keys as $key) {
                $element = $this->form->getElement($key);
                if (!$element) {
                    continue;
                }

                $html .= (new ElementRenderer($this->form))->render($element);
            }
        }

        return $html;
    }

    public function __toString(): string
    {
        return $this->render();
    }

//    private function elementsGenerator(Form $form): \Generator
//    {
//        foreach ($form->getElements() as $element) {
//            if ($element->isRenderable() && !$element->isRendered()) {
//                yield (new ElementRenderer($this->form))->render($element);
//            }
//        }
//    }
}
