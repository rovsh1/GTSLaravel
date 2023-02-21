<?php

namespace App\Admin\Http\View\Grid;

use App\Admin\Http\View\Navigation\Paginator;

class ModelGrid extends Grid
{
    protected $model;

    public function __construct($options = [])
    {
        parent::__construct($options);

        $paginator = new Paginator();
        $paginator->setStep(20);
        $paginator->setCount($this->count());

        $this
            ->paginator($paginator)
            ->data($this->query());
    }

    protected function queryFactory()
    {
        return $this->model::query();
    }

    protected function applyQuicksearch($query, string $term)
    {
        $query->quicksearch($term);
    }

    protected function applySearch($query, array $filters) {}

    protected function prepareSelectQuery($query) {}

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

    private function applyFilters($query)
    {
        if (isset($filters['quicksearch'])) {
            $this->applyQuicksearch($query, $filters['quicksearch']);
        }

        if ($this->searchForm) {
            $this->applySearch($query, array_filter($this->searchForm->getData()));
        }
    }
}
