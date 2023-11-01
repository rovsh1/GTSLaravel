<?php

namespace Module\Generic\NotificationManager\Infrastructure\Adapter;

class MailAdapter
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