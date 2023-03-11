<?php

namespace App\Admin\Support\View\Sitemap;

use App\Admin\Support\View\Sitemap\Menu\CategoryMenu;
use App\Admin\Support\View\Sitemap\Menu\ItemInterface;
use App\Admin\Support\View\Sitemap\Support\SitemapBuilder;

class Sitemap
{
    private ?string $current = null;

    private ?CategoryMenu $category = null;

    private readonly array $categoriesMenu;

    public function __construct()
    {
        $this->categoriesMenu = (new SitemapBuilder(app('factory.prototypes')))->build();
    }

    public function currentRoute(string $key): void
    {
        foreach ($this->categoriesMenu as $menu) {
            $item = $menu->getItem($key);
            if ($item) {
                $this->category = $menu;
                $this->current = $key;
                return;
            }
        }

        $this->category = null;
        $this->current = null;
    }

    public function currentCategory(string $category): void
    {
        foreach ($this->categoriesMenu as $menu) {
            if ($menu->key === $category) {
                $this->category = $menu;
                return;
            }
        }
    }

    public function isCurrent(ItemInterface|CategoryMenu $item): bool
    {
        if (!$this->category) {
            return false;
        } elseif ($item instanceof CategoryMenu) {
            return $this->category->key === $item->key;
        } else {
            return $this->current === $item->key;
        }
    }

    public function getCurrentRoute(): ?string
    {
        return $this->current;
    }

    public function getCurrentCategory(): ?CategoryMenu
    {
        return $this->category;
    }

    public function render()
    {
        if (null === $this->category) {
            $this->detectCurrent();
        }

        return view('layouts/sitemap/sitemap', [
            'sitemap' => $this,
            'categories' => $this->categoriesMenu
        ]);
    }

    private function detectCurrent(): void
    {
        $route = request()->route()->getName();
        $this->currentRoute($route);
        if (null !== $this->current || str_ends_with($route, '.index')) {
            return;
        }

        $route = substr($route, 0, strrpos($route, '.')) . '.index';
        $this->currentRoute($route);
    }
}
