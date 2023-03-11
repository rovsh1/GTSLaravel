<?php

namespace App\Admin\Support\View\Navigation;

use App\Admin\Support\Facades\Sitemap;

class Sidebar
{
    public function currentRoute(string $key): void
    {
        Sitemap::currentRoute($key);
    }

    public function currentCategory(string $key): void
    {
        Sitemap::currentCategory($key);
    }

    public function render()
    {
        $category = Sitemap::getCurrentCategory();
        if (!$category) {
            return '';
        }

        $menu = null;

        return view('layouts/main/sidebar', [
            'sitemap' => Sitemap::getFacadeRoot(),
            'sidebar' => $this,
            'category' => $category,
            'menu' => $menu
        ]);
    }
}
