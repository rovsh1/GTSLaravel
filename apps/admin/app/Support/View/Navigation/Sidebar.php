<?php

namespace App\Admin\Support\View\Navigation;

class Sidebar
{
    public function currentRoute(string $key): void
    {
        app('sitemap')->currentRoute($key);
    }

    public function currentCategory(string $key): void
    {
        app('sitemap')->currentCategory($key);
    }

    public function render()
    {
        $category = app('sitemap')->getCurrentCategory();
        if (!$category) {
            return '';
        }

        $menu = null;

        return view('layouts/main/sidebar', [
            'sitemap' => app('sitemap'),
            'sidebar' => $this,
            'category' => $category,
            'menu' => $menu
        ]);
    }
}
