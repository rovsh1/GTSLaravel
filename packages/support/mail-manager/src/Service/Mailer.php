<?php

namespace Pkg\MailManager\Service;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail as LaravelMail;
use Pkg\MailManager\Contracts\MailerInterface;
use Pkg\MailManager\Mail;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;

class Mailer implements MailerInterface
{
    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {}

    public function send(Mail $message): void
    {
        $mailerMessage = new Mailable();
        $mailerMessage->to($message->to()->toArray());
        $mailerMessage->subject($message->subject());
        $mailerMessage->html($message->body()->value());
        foreach ($message->attachments() as $file) {
            $fileInfo = $this->fileStorageAdapter->getInfo($file->guid());
            $mailerMessage->attach($fileInfo->filename, [
                'as' => $fileInfo->name,
                'mime' => $fileInfo->mimeType
            ]);
        }

        $sentMessage = LaravelMail::send($mailerMessage);
    }
}