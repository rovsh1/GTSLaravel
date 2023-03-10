<?php

namespace App\Admin\Components\Sidebar;

use App\Admin\Components\Sidebar\Menu\CategoryMenu;
use App\Admin\Components\Sidebar\Menu\ItemInterface;
use App\Admin\Components\Sidebar\Support\SidebarBuilder;

class Sidebar
{
    private ?\stdClass $current = null;

    private readonly array $categoriesMenu;

    public function __construct()
    {
        $this->categoriesMenu = (new SidebarBuilder(app('factory.prototypes')))->build();
    }

    public function current(string $key): void
    {
        foreach ($this->categoriesMenu as $menu) {
            $item = $menu->getItem($key);
            if ($item) {
                $current = new \stdClass();
                $current->key = $key;
                $current->category = $menu;
                $this->current = $current;
                return;
            }
        }

        $this->current = null;
    }

    public function menu($menu, ...$arguments) {}

    public function isCurrent(ItemInterface|CategoryMenu $item): bool
    {
        if (!$this->current) {
            return false;
        } elseif ($item instanceof CategoryMenu) {
            return $this->current->category->key === $item->key;
        } else {
            return $this->current->key === $item->key;
        }
    }

    public function render()
    {
        $menu = null;
        if (null === $this->current) {
            $this->detectCurrent();
        }

        return view('layouts/main/sidebar', [
            'sidebar' => $this,
            'displayCategories' => count($this->categoriesMenu) > 1,
            'categories' => $this->categoriesMenu,
            'current' => $this->current,
            'menu' => $menu
        ]);
    }

    private function detectCurrent(): void
    {
        $this->current(request()->route()->getName());
    }
}
