<?php

namespace App\Admin\Support\Http\Resources;

use App\Admin\View\Form\Form;
use App\Admin\View\Grid\Grid;
use App\Admin\View\Grid\Quicksearch;
use App\Admin\View\Navigation\Paginator;

abstract class AbstractGridResource extends AbstractLayoutResource
{
    protected string $model;

    protected string $view = 'default.search';

    protected ?Form $searchForm = null;

    protected ?Quicksearch $quicksearch = null;

    protected Paginator $paginator;

    protected Grid $grid;

    protected int $paginatorStep = 20;

    public function __construct()
    {
        $this->paginator = new Paginator($this->paginatorStep);
        $this->grid = $this->grid();

        parent::__construct();
    }

    protected function build()
    {
        parent::build();

        $this->paginator->setCount($this->queryCount());

        $this->grid->data($this->querySelect());
    }

    protected function enableQuicksearch(): void
    {
        $this->quicksearch = new Quicksearch(['method' => 'GET']);
        $this->quicksearch->text('quicksearch', ['placeholder' => 'Быстрый поиск', 'autocomplete' => false]);
        $this->quicksearch->submit();
    }

    protected function setSearchForm(Form $form): void
    {
        $this->searchForm = $form;
        $this->searchForm->submit();
    }

    protected function getViewData(): array
    {
        return [
            'quicksearch' > $this->quicksearch,
            'searchForm' => $this->searchForm,
            'grid' => $this->grid,
            'paginator' => $this->paginator
        ];
    }

    abstract protected function grid(): Grid;

    protected function query()
    {
        return $this->model::query();
    }

    protected function querySelect()
    {
        $query = $this->query();

        $this->applyFilters($query);

        $this->prepareSelectQuery($query);

        return $query;
    }

    protected function queryCount(): int
    {
        $query = $this->query();

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
