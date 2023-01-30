<?php

namespace GTS\Shared\UI\Admin\View\Sidebar;

class SidebarGroup
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
    ) {}
}
