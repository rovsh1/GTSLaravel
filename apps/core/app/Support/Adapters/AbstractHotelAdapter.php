<?php

namespace App\Core\Support\Adapters;

abstract class AbstractHotelAdapter extends AbstractModuleAdapter
{
    final protected function getModuleKey(): string
    {
        return 'hotel';
    }
}