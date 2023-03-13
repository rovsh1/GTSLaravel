<?php

namespace App\Admin\Support\View\Sitemap\Support;

use App\Admin\Components\Factory\PrototypesCollection;
use App\Admin\Support\View\Sidebar\Menu\Group;
use App\Admin\Support\View\Sidebar\Menu\Item;
use App\Admin\Support\View\Sitemap\CategoryMenu;

class SitemapBuilder
{
    private static array $buildCategories = [
        'reservation',
        'hotel',
        'finance',
        'client',
        'site',
        'reports',
        'administration'
    ];

    public function __construct(private readonly PrototypesCollection $prototypes) {}

    public function build(): array
    {
        $menus = [];
        foreach (self::$buildCategories as $case) {
            $menu = $this->makeCategory($case);
            if (!$menu->isEmpty()) {
                $menus[] = $menu;
            }
        }
        return $menus;
    }

    public function makeCategory(string $category): CategoryMenu
    {
        $menu = new CategoryMenu($category, __('category.' . $category));

        $prototypes = array_filter($this->prototypes->all(), fn($p) => $p->category === $category);
        $this->sortPrototypes($prototypes);

        $mainPrototypes = array_filter($prototypes, fn($p) => $p->group === 'main');
        foreach ($mainPrototypes as $prototype) {
            $item = $this->makeItem($prototype);
            if ($item) {
                $menu->addItem($item);
            }
        }

        $createdGroups = ['main'];
        foreach ($prototypes as $prototype) {
            if (!in_array($prototype->group, $createdGroups)) {
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
        $this->sortPrototypes($prototypes);
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

    private function sortPrototypes(&$prototypes): void
    {
        usort($prototypes, function ($a, $b) {
            if ($a->config('priority') === $b->config('priority')) {
                return 0;
            } else {
                return $a->config('priority') > $b->config('priority') ? -1 : 1;
            }
        });
    }
}
