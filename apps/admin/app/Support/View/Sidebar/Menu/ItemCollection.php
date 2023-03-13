<?php

namespace App\Admin\Support\View\Sidebar\Menu;

class ItemCollection
{
    protected array $items = [];

    public function addItem(ItemInterface $item): static
    {
        $this->items[] = $item;
        return $this;
    }

    public function addUrl(string $key, string $url, string $text, array $options = []): static
    {
        return $this->addItem(
            new Item(
                array_merge($options, [
                    'key' => $key,
                    'url' => $url,
                    'text' => $text
                ])
            )
        );
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
