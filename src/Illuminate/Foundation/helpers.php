<?php

use Custom\Framework\Foundation\Module;

function module(string $name): ?Module
{
    return app('modules')->get($name);
}
