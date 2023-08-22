<?php

use Sdk\Module\Foundation\Module;

function module(string $name): ?Module
{
    return app('modules')->get($name);
}

function root_path($path = '')
{
    return app()->rootPath($path);
}

function core_path($path = ''): string
{
    return app()->corePath($path);
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

function modules_path($path = '')
{
    return app()->modulesPath($path);
}
