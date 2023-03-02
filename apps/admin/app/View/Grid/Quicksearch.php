<?php

namespace App\Admin\View\Grid;

use App\Admin\View\Form\Form;

class Quicksearch extends Form
{
    protected function boot()
    {
        $this->method('get');
    }

    public function __toString(): string
    {
        $html = '<form method="get" class="quicksearch">';
        $html .= '<button type="submit" title="Найти"></button>';
        $html .= $this->render();
        $html .= '</form>';
        return $html;
    }

    public function getTerm(): ?string
    {
        return $this->getElement('quicksearch')->getValue();
    }
}
