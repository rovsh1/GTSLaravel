<?php

namespace Module\Services\NotificationManager\Infrastructure\Adapter;

use Module\Shared\Infrastructure\Adapter\AbstractPortAdapter;

class SmsAdapter extends AbstractPortAdapter
{
    public function sendText(string $phone, string $text): void
    {
        $uuid = $this->request('sms/send', [
            'to' => $phone,
            'text' => $text,
        ]);
    }
}