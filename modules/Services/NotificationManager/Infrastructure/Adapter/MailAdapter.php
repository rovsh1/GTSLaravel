<?php

namespace Module\Services\NotificationManager\Infrastructure\Adapter;

use Module\Shared\Infrastructure\Adapter\AbstractPortAdapter;

class MailAdapter extends AbstractPortAdapter
{
    public function sendTo(string $to, string $subject, string $body): void
    {
        $uuid = $this->request('mail/send', [
            'to' => $to,
            'subject' => $subject,
            'body' => $body
        ]);
//        return $this->fileFactory($fileDto);
    }
}