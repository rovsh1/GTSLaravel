<?php

use GTS\Shared\Infrastructure\Support\Module\Module;

function module(string $name): ?Module
{
    return app('modules')->get($name);
}
