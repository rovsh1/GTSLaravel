<?php

namespace App\Hotel\Support\View\Sitemap;

use App\Hotel\Support\Facades\Sitemap;
use App\Hotel\Support\View\Sidebar\Menu\ItemInterface;
use App\Hotel\Support\View\Sidebar\Menu\AbstractMenu;

class CategoryMenu extends AbstractMenu
{
    public function __construct(
        public readonly string $key,
        public readonly string $title
    ) {}

    public function isCurrent(string|ItemInterface $key): bool
    {
        return Sitemap::isCurrent($key);
    }
}
