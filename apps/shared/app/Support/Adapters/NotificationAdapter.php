<?php

namespace App\Shared\Support\Adapters;

class NotificationAdapter extends AbstractModuleAdapter
{
    protected function getModuleKey(): string
    {
        return 'notification';
    }
}