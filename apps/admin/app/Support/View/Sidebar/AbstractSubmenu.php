<?php

namespace App\Admin\Support\View\Sidebar;

use App\Admin\Support\View\Sidebar\Menu\ItemInterface;
use App\Admin\Support\View\Sidebar\Menu\AbstractMenu;

abstract class AbstractSubmenu extends AbstractMenu
{
    public function __construct(
        protected readonly string $title,
        private readonly ?string $current = null
    ) {}

    public function title(): string
    {
        return $this->title;
    }

    public function current() {}

    public function isCurrent(string|ItemInterface $key): bool
    {
        return is_string($key)
            ? $key === $this->current
            : $key->key === $this->current;
    }
}
