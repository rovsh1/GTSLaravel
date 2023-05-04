<?php

namespace App\Core\Support\Adapters;

class NotificationAdapter extends AbstractModuleAdapter
{
    protected function getModuleKey(): string
    {
        return 'notification';
    }
}