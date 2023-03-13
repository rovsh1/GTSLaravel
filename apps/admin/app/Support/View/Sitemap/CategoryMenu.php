<?php

namespace App\Admin\Support\View\Sitemap;

use App\Admin\Support\Facades\Sitemap;
use App\Admin\Support\View\Sidebar\Menu\ItemInterface;
use App\Admin\Support\View\Sidebar\Menu\AbstractMenu;

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
