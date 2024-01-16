<?php

use Sdk\Module\Contracts\ModuleInterface;

function module(string $name): ?ModuleInterface
{
    return app('modules')->get($name);
}

function admin_path($path = ''): string
{
    return app('path.admin') . ($path ? DIRECTORY_SEPARATOR . $path : '');
}

function site_path($path = ''): string
{
    return app('path.site') . ($path ? DIRECTORY_SEPARATOR . $path : '');
}

function api_path($path = ''): string
{
    return app('path.api') . ($path ? DIRECTORY_SEPARATOR . $path : '');
}

function modules_path($path = ''): string
{
    return app()->modulesPath($path);
}

function package_path($path = ''): string
{
    return app()->packagePath($path);
}
