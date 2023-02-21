<?php

namespace App\Admin\Http\View\Grid;

use App\Admin\Http\View\Form\Form;
use App\Admin\Http\View\Navigation\Paginator;

class GridBuilder
{
    public const DEFAULT_LIMIT = 30;

    private readonly Grid $grid;

    public function __construct($options = [])
    {
        $this->grid = new Grid($options);
    }

    public function __call(string $name, array $arguments)
    {
        $this->grid->$name(...$arguments);

        return $this;
    }

    public function paginator(int $count, $limit = self::DEFAULT_LIMIT): static
    {
        $paginator = new Paginator();
        $paginator->setStep($limit);
        $paginator->setCount($count);

        $this->grid->paginator($paginator);

        return $this;
    }

    public function quicksearch(): static
    {
        $this->grid->enableQuicksearch();
        return $this;
    }

    public function search(Form $form): static
    {
        $this->grid->setSearchForm($form);
        return $this;
    }

    public function callFacadeSearch($facade, $dto): static
    {
        $this->prepareDto($dto);

        $this->grid->data($facade->search($dto));

        return $this;
    }

    public function getGrid(): Grid
    {
        //dd($this->grid->getColumns());
        return $this->grid;
    }

    public function __toString(): string
    {
        return (string)$this->grid;
    }

    private function prepareDto($dto): void
    {
        $sorting = $this->grid->getSorting();
        $sorting->fromRequest();
        $dto->orderBy = $sorting->orderby;
        $dto->sortOrder = $sorting->sortorder;

        $paginator = $this->grid->getPaginator();
        $dto->offset = $paginator->getOffset();
        $dto->limit = $paginator->step;

        if ($this->grid->quicksearch && ($term = $this->grid->quicksearch->getTerm())) {
            $dto->quicksearch = $term;
        }

        if (($search = $this->grid->search)) {
            foreach ($search->getData() as $k => $v) {
                $dto->$k = $v;
            }
        }
    }
}
