<?php

namespace Sdk\Module\Support;

use Traversable;

/**
 * @template TKey of array-key
 *
 * @template-covariant TValue
 *
 * @implements \Iterator<TKey, TValue>
 * @implements \Countable<TKey, TValue>
 */
class Collection implements \Iterator, \Countable
{
    use ItemCollectionIteratorTrait;

    public static function make(iterable $items = []): static
    {
        return new static($items);
    }

    public function __construct(iterable $items = [])
    {
        $this->items = $this->getArrayableItems($items);
    }

    public function all(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function isNotEmpty(): bool
    {
        return !empty($this->items);
    }

    public function map(callable $callback): array
    {
        return array_map($callback, $this->items);
    }

    protected function getArrayableItems($items): array
    {
        if (is_array($items)) {
            return $items;
        } elseif ($items instanceof Traversable) {
            return iterator_to_array($items);
        }

        return (array)$items;
    }
}
