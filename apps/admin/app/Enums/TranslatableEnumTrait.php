<?php

namespace App\Admin\Enums;

trait TranslatableEnumTrait
{
    public function getLabel(): string
    {
        $prefix = self::LANG_PREFIX;
        return __("{$prefix}.{$this->name}");
    }
}
