<?php

namespace App\Admin\Support\View\Sitemap;

use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\View\Sidebar\Menu\ItemInterface;

class Sitemap
{
    private ?string $current = null;

    private ?CategoryMenu $category = null;

    private readonly array $categoriesMenu;

    public function __construct()
    {
        $this->categoriesMenu = (new SitemapBuilder(Prototypes::getFacadeRoot(), Acl::getFacadeRoot()))->build();
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

    public function isCurrent(string|ItemInterface|CategoryMenu $item): bool
    {
        if (!$this->category) {
            return false;
        }

        if ($item instanceof CategoryMenu) {
            return $this->category->key === $item->key;
        } elseif ($item instanceof ItemInterface) {
            return $this->current === $item->key;
        } else {
            return $this->current === $item;
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

    public function getCategories(): array
    {
        return $this->categoriesMenu;
    }

    public function render()
    {
        if (null === $this->category) {
            $this->detectCurrent();
        }

        return view('layouts/main/sitemap', [
            'sitemap' => $this,
            'categories' => $this->categoriesMenu
        ]);
    }

    private function detectCurrent(): void
    {
        $route = request()->route();
        if (!$route) {
            return;
        }
        $routeName = request()->route()->getName();
        $this->currentRoute($routeName);
        if (null !== $this->current) {
            return;
        }

        $routeName = substr($routeName, 0, strpos($routeName, '.')) . '.index';
        $this->currentRoute($routeName);
    }
}
