<?php

use GTS\Shared\Custom\Foundation\Module;

function module(string $name): ?Module
{
    return app('modules')->get($name);
}
