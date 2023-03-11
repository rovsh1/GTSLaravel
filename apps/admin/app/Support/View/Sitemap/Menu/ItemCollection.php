<?php

namespace App\Admin\Support\View\Sitemap\Menu;

class ItemCollection
{
    protected array $items = [];

    public function addItem(ItemInterface $item): void
    {
        $this->items[] = $item;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function items(): array
    {
        return $this->items;
    }

    public function getItem(string $key): ?ItemInterface
    {
        foreach ($this->items as $item) {
            if ($item->key === $key) {
                return $item;
            }
        }
        return null;
    }
}
