<?php

namespace App\Admin\Support\View\Sidebar\Menu;

interface MenuInterface
{
    public function isEmpty(): bool;

    public function addItem(ItemInterface $item): static;

    public function getItem(string $key): ?ItemInterface;
}
