<?php

namespace Sdk\Module\Support;

/**
 * @template TKey of array-key
 *
 * @template-covariant TValue
 *
 * @implements \Iterator<TKey, TValue>
 * @implements \Countable<TKey, TValue>
 */
abstract class AbstractValueObjectCollection extends Collection
{
    public function __construct(iterable $items = [])
    {
        $this->validateItems($items);
        parent::__construct($items);
    }

    protected function validateItem(mixed $item): void
    {
    }

    protected function validateItems(iterable $items): void
    {
        foreach ($items as $item) {
            $this->validateItem($item);
        }
    }
}
