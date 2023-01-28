<?php

namespace GTS\Shared\UI\Admin\View\Grid;

use GTS\Shared\UI\Admin\View\Navigation\Paginator;

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

    public function callFacadeSearch($facade, $dto): static
    {
        $this->prepareDto($dto);

        $this->grid->data($facade->search($dto));

        return $this;
    }

    public function getGrid(): Grid
    {
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
    }
}
