<?php

namespace App\Admin\Support\Facades;

use App\Admin\Support\View\JsVariablesManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static JsVariablesManager add(string|array $name, mixed $value = null)
 * @method static string render()
 */
class JsVariables extends Facade
{
    protected static function getFacadeAccessor()
    {
        return JsVariablesManager::class;
    }
}