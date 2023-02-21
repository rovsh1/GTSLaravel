<?php

namespace App\Admin\Http\View\Sidebar;

class SidebarGroup
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
    ) {}
}
