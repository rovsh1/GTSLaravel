<?php

namespace App\Admin\Support\View\Sitemap\Menu;

class CategoryMenu extends ItemCollection implements MenuInterface
{
    public readonly string $title;

    private array $groups = [];

    public function __construct(public readonly string $key)
    {
        $this->title = __('category.' . $this->key);
    }

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
}
