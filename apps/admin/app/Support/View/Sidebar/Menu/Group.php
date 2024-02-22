<?php

namespace App\Admin\Support\View\Sidebar\Menu;

class Group extends ItemCollection implements MenuInterface
{
    public readonly string $title;

    public function __construct(public readonly string $key)
    {
        $this->title = __('group.' . $this->key);
    }
}
