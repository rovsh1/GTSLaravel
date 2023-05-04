<?php

namespace App\Admin\Support\Adapters;

use App\Core\Support\Adapters\AbstractMailAdapter;
use App\Core\Support\Facades\AppContext;

class MailAdapter extends AbstractMailAdapter
{
    public function sendTo(string $to, string $subject, string $body): void
    {
        $uuid = $this->request('send', [
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
            'context' => AppContext::get()
        ]);
//        dd($uuid);
//        return $this->fileFactory($fileDto);
    }

    public function getQueue(array $criteria = [])
    {
        return $this->request('get-queue', $criteria);
    }

    public function getTemplatesList(): array
    {
        return $this->request('templates-list');
    }
}