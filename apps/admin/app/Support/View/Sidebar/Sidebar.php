<?php

namespace App\Admin\Support\View\Sidebar;

use App\Admin\Support\Facades\Sitemap;

class Sidebar
{
    private ?AbstractSubmenu $submenu = null;

    public function __construct() { }

    public function currentRoute(string $key): void
    {
        Sitemap::currentRoute($key);
    }

    public function currentCategory(string $key): void
    {
        Sitemap::currentCategory($key);
    }

    public function submenu(AbstractSubmenu $menu): void
    {
        $this->submenu = $menu;
    }

    public function isExpanded(): bool
    {
        $isExpanded = $_COOKIE['menu_open_flag'] ?? null;

        return $isExpanded === 'true';
    }

    public function render()
    {
        $category = Sitemap::getCurrentCategory();
        if (!$category) {
            return '';
        }

        return view('layouts/main/sidebar', [
            'sitemap' => Sitemap::getFacadeRoot(),
            'sidebar' => $this,
            'submenu' => $this->submenu,
            'category' => $category
        ]);
    }
}
