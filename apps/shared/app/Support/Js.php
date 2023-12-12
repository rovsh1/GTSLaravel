<?php

namespace App\Shared\Support;

class Js extends \Illuminate\Support\Js
{
    public static function variables(array $values): JsVariables
    {
        return new JsVariables($values);
    }
}