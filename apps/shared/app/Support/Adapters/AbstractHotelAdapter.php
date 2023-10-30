<?php

namespace App\Shared\Support\Adapters;

abstract class AbstractHotelAdapter extends AbstractModuleAdapter
{
    final protected function getModuleKey(): string
    {
        return 'catalog';
    }
}