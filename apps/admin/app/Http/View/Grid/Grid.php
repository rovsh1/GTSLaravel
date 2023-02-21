<?php

namespace App\Admin\Http\View\Grid;

use App\Admin\Http\View\Form\Form;
use Gsdk\Grid\Grid as Base;

class Grid extends Base
{
    protected ?Form $searchForm = null;

    protected ?Quicksearch $quicksearch = null;

    public function __get(string $name)
    {
        return match ($name) {
            'quicksearch' => $this->quicksearch,
            'search' => $this->searchForm,
            default => parent::__get($name)
        };
    }

    public function enableQuicksearch(): static
    {
        $this->quicksearch = new Quicksearch(['method' => 'GET']);
        $this->quicksearch->text('quicksearch', ['placeholder' => 'Быстрый поиск', 'autocomplete' => false]);
        $this->quicksearch->submit();
        return $this;
    }

    public function setSearchForm(Form $form): static
    {
        $this->searchForm = $form;
        $this->searchForm->submit();
        return $this;
    }
}
