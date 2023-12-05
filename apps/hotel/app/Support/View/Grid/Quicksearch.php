<?php

namespace App\Hotel\Support\View\Grid;

use App\Hotel\Support\View\Form\FormBuilder;

class Quicksearch extends FormBuilder
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
