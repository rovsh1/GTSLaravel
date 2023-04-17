<?php

namespace App\Core\Support\Adapters;

class MailAdapter extends AbstractPortAdapter
{
    protected string $module = 'mail';

    public function sendTo(string $to, string $subject, string $body): void
    {
        $uuid = $this->request('send', [
            'to' => $to,
            'subject' => $subject,
            'body' => $body
        ]);
//        return $this->fileFactory($fileDto);
    }
}