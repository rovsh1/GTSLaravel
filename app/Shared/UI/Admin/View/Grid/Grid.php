<?php

namespace GTS\Shared\UI\Admin\View\Grid;

use Gsdk\Grid\Grid as Base;
use GTS\Shared\UI\Admin\View\Form\Form;

class Grid extends Base
{
    protected Form $searchForm;

    protected Quicksearch $quicksearch;

    public function __get(string $name)
    {
        return match ($name) {
            'quicksearch' => $this->quicksearch,
            'search' => $this->searchForm,
            default => parent::__get($name)
        };
    }

    public function enableQuicksearch(): void
    {
        $this->quicksearch = new Quicksearch(['method' => 'GET']);
        $this->quicksearch->text('quicksearch', ['placeholder' => 'Быстрый поиск', 'autocomplete' => false]);
        $this->quicksearch->submit();
    }

    public function setSearchForm(Form $form): void
    {
        $this->searchForm = $form;
        $this->searchForm->submit();
    }
}
