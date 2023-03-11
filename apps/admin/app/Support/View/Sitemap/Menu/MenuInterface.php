<?php

namespace App\Admin\Support\View\Sitemap\Menu;

interface MenuInterface
{
    public function isEmpty(): bool;

    public function addItem(ItemInterface $item): void;

    public function getItem(string $key): ?ItemInterface;
}
