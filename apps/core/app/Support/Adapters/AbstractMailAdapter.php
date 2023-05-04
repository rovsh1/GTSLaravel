<?php

namespace App\Core\Support\Adapters;

abstract class AbstractMailAdapter extends AbstractModuleAdapter
{
    final protected function getModuleKey(): string
    {
        return 'mail';
    }
}