<?php

namespace GTS\Shared\UI\Port\Routing;

use GTS\Shared\Custom\Foundation\Module;

class Router
{
    public function __construct(private readonly Module $module) {}

    public function loadRoutes($path): void
    {
        include $path;
    }
}
