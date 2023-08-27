<?php

namespace Sdk\Module\Support;

/**
 * @template TKey of array-key
 *
 * @template-covariant TValue
 */
trait ItemCollectionIteratorTrait
{
    /** @var array<TKey, TValue>  */
    protected array $items = [];

    private int $position = 0;

    public function current()
    {
        return $this->items[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}
