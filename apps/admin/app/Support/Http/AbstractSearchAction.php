<?php

namespace App\Admin\Support\Http;

use App\Admin\Http\View\Form\Form;
use App\Admin\Http\View\Grid\Grid;
use App\Admin\Http\View\Grid\Quicksearch;
use App\Admin\Http\View\Navigation\Paginator;

abstract class AbstractSearchAction extends AbstractAction
{
    protected $model;

    protected $view = 'default.search';

    protected ?Form $searchForm = null;

    protected ?Quicksearch $quicksearch = null;

    protected Paginator $paginator;

    protected $paginatorStep = 20;

    public function __construct()
    {
        $this->paginator = new Paginator($this->paginatorStep);
    }

    protected function enableQuicksearch(): static
    {
        $this->quicksearch = new Quicksearch(['method' => 'GET']);
        $this->quicksearch->text('quicksearch', ['placeholder' => 'Быстрый поиск', 'autocomplete' => false]);
        $this->quicksearch->submit();
        return $this;
    }

    protected function setSearchForm(Form $form): static
    {
        $this->searchForm = $form;
        $this->searchForm->submit();
        return $this;
    }

    protected function getViewData()
    {
        $grid = $this->gridFactory();
        $grid->data($this->query());

        $this->paginator->setCount($this->count());

        return [
            'quicksearch' > $this->quicksearch,
            'searchForm' => $this->searchForm,
            'grid' => $grid,
            'paginator' => $this->paginator
        ];
    }

    protected function gridFactory()
    {
        return new Grid();
    }

    protected function queryFactory()
    {
        return $this->model::query();
    }

    protected function query()
    {
        $query = $this->queryFactory();

        $this->applyFilters($query);

        $this->prepareSelectQuery($query);

        return $query;
    }

    protected function count(): int
    {
        $query = $this->queryFactory();

        $this->applyFilters($query);

        return $query->count();
    }

    protected function applyQuicksearch($query, string $term)
    {
        $query->quicksearch($term);
    }

    protected function applySearch($query, array $filters) {}

    protected function prepareSelectQuery($query) {}

    private function applyFilters($query)
    {
        if ($this->quicksearch && ($term = $this->quicksearch->getElement('quicksearch')->getValue())) {
            $this->applyQuicksearch($query, $term);
        }

        if ($this->searchForm) {
            $this->applySearch($query, array_filter($this->searchForm->getData()));
        }

        $this->paginator->query($query);
    }
}
