<?php

namespace App\Admin\Components\Sidebar\Support;

use App\Admin\Components\Factory\PrototypesCollection;
use App\Admin\Components\Sidebar\Categories;
use App\Admin\Components\Sidebar\Menu\CategoryMenu;
use App\Admin\Components\Sidebar\Menu\Group;
use App\Admin\Components\Sidebar\Menu\Item;

class SidebarBuilder
{
    private array $categoryIcons = [
        'administration' => 'settings',
        'client' => 'person',
        'finances' => 'payments',
        'hotel' => 'hotel',
        'reports' => 'bar_chart',
        'reservation' => 'airplane_ticket',
        'site' => 'language',
    ];

    public function __construct(private readonly PrototypesCollection $prototypes) {}

    public function build(): array
    {
        $menus = [];
        foreach (Categories::cases() as $case) {
            $menu = $this->makeCategory($case->value);
            if (!$menu->isEmpty()) {
                $menus[] = $menu;
            }
        }
        return $menus;
    }

    private function makeCategory($category): CategoryMenu
    {
        $menu = new CategoryMenu([
            'key' => $category,
            'icon' => $this->categoryIcons[$category]
        ]);
        $prototypes = array_filter($this->prototypes->all(), fn($p) => $p->category === $category);
        $createdGroups = [];
        foreach ($prototypes as $prototype) {
            if ($prototype->group === 'main') {
                $item = $this->makeItem($prototype);
                if ($item) {
                    $menu->addItem($item);
                }
            } elseif (!in_array($prototype->group, $createdGroups)) {
                $createdGroups[] = $prototype->group;
                $group = $this->makeGroup($category, $prototype->group);
                if (!$group->isEmpty()) {
                    $menu->addGroup($group);
                }
            }
        }
        return $menu;
    }

    private function makeGroup($category, $group): Group
    {
        $menuGroup = new Group($group);
        $prototypes = array_filter($this->prototypes->all(), fn($p) => $p->category === $category && $p->group === $group);
        foreach ($prototypes as $prototype) {
            $item = $this->makeItem($prototype);
            if ($item) {
                $menuGroup->addItem($item);
            }
        }
        return $menuGroup;
    }

    private function makeItem($prototype): ?Item
    {
        return new Item([
            'key' => $prototype->routeName('index'),
            'url' => $prototype->route(),
            'text' => $prototype->title(),
        ]);
    }
}
