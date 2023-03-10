<?php

namespace App\Admin\Components\Sidebar\Menu;

interface MenuInterface
{
    public function isEmpty(): bool;

    public function addItem(ItemInterface $item): void;

    public function getItem(string $key): ?ItemInterface;
}
