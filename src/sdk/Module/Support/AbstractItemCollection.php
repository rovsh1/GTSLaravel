<?php

namespace Sdk\Module\Support;

abstract class AbstractItemCollection extends Collection
{
    public function __construct(iterable $items = [])
    {
        $this->validateItems($items);
        parent::__construct($items);
    }

    /**
     * @param mixed $item
     * @return void
     */
    protected function validateItem($item): void
    {
    }

    protected function validateItems(iterable $items): void
    {
        foreach ($items as $item) {
            $this->validateItem($item);
        }
    }
}