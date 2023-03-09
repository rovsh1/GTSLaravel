<?php

namespace App\Admin\Support\View\Grid;

use App\Admin\Support\View\Form\Form;

class Quicksearch extends Form
{
    protected function build()
    {
        $this->method('get')
            ->search('quicksearch', ['placeholder' => 'Быстрый поиск', 'autocomplete' => 'off']);
    }

    public function __toString(): string
    {
        return $this->getElement('quicksearch')->render();
    }

    public function getTerm(): ?string
    {
        return $this->getElement('quicksearch')->getValue();
    }
}
