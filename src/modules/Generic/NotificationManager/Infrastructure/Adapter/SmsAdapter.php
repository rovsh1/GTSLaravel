<?php

namespace Module\Generic\NotificationManager\Infrastructure\Adapter;

class SmsAdapter
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