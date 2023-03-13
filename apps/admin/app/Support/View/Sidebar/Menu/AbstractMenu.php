<?php

namespace App\Admin\Support\View\Sidebar\Menu;

abstract class AbstractMenu extends ItemCollection implements MenuInterface
{
    protected array $groups = [];

    public function groups(): array
    {
        return $this->groups;
    }

    public function addGroup(Group $group): void
    {
        $this->groups[] = $group;
    }

    public function getItem(string $key): ?ItemInterface
    {
        $item = parent::getItem($key);
        if ($item) {
            return $item;
        }

        foreach ($this->groups as $group) {
            $item = $group->getItem($key);
            if ($item) {
                return $item;
            }
        }

        return null;
    }

    public function isEmpty(): bool
    {
        return parent::isEmpty() && empty($this->groups);
    }

    public function render()
    {
        return view('layouts/main/sidebar-menu', [
            'menu' => $this
        ]);
    }
}
