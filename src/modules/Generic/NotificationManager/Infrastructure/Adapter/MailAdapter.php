<?php

namespace Module\Generic\NotificationManager\Infrastructure\Adapter;

use Module\Shared\Infrastructure\Adapter\AbstractModuleAdapter;

class MailAdapter extends AbstractModuleAdapter
{
    public function sendTo(string $to, string $subject, string $body): void
    {
        $uuid = $this->request('send', [
            'to' => $to,
            'subject' => $subject,
            'body' => $body
        ]);
//        return $this->fileFactory($fileDto);
    }

    protected function getModuleKey(): string
    {
        return 'mail';
    }
}