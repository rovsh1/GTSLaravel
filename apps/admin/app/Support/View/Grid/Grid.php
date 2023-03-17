<?php

namespace App\Admin\Support\View\Grid;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Support\View\Form\Form;
use Gsdk\Grid\Grid as Base;

class Grid extends Base
{
    protected ?Form $searchForm = null;

    protected ?Quicksearch $quicksearch = null;

    public function edit(Prototype|string|array $options): static
    {
        if (is_string($options)) {
            $options = ['route' => $options];
        } elseif ($options instanceof Prototype) {
            $options = ['route' => $options->routeName('edit')];
        }
        return $this->addColumn(new Column\Edit('edit', $options));
    }

    public function actions(array $options = []): static
    {
        return $this->addColumn(new Column\Actions('actions', $options));
    }

    public function getSearchCriteria(): array
    {
        $criteria = [];

        if ($this->searchForm) {
            $criteria = array_filter($this->searchForm->getData());
        }

        if ($this->quicksearch && ($term = $this->quicksearch->getTerm())) {
            $criteria['quicksearch'] = $term;
        }

        return $criteria;
    }

    public function enableQuicksearch(): static
    {
        $this->quicksearch = new Quicksearch();
        $this->quicksearch->submit();
        return $this;
    }

    public function getQuicksearch(): ?Quicksearch
    {
        return $this->quicksearch;
    }

    public function setSearchForm(Form $form): static
    {
        $this->searchForm = $form;
        $this->searchForm->submit();
        return $this;
    }

    public function getSearchForm(): ?Form
    {
        return $this->searchForm;
    }
}
