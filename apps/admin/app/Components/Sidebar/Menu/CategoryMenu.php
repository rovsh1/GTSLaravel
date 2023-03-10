<?php

namespace App\Admin\Components\Sidebar\Menu;

class CategoryMenu extends ItemCollection implements MenuInterface
{
    public readonly string $key;

    public readonly string $title;

    public readonly string $icon;

    private array $groups = [];

    public function __construct(array $options)
    {
        $this->key = $options['key'];
        $this->icon = $options['icon'];
        $this->title = __($options['key']);
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
