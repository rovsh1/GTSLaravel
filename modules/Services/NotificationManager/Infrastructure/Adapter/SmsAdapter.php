<?php

namespace Module\Services\NotificationManager\Infrastructure\Adapter;

use Module\Shared\Infrastructure\Adapter\AbstractModuleAdapter;

class SmsAdapter extends AbstractModuleAdapter
{
    public function sendText(string $phone, string $text): void
    {
        $uuid = $this->request('send', [
            'to' => $phone,
            'text' => $text,
        ]);
    }

    protected function getModuleKey(): string
    {
        return 'sms';
    }
}