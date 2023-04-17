<?php

namespace Module\Services\MailManager\Infrastructure\Service;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail as LaravelMail;
use Module\Services\MailManager\Domain\Entity\Mail;
use Module\Services\MailManager\Domain\Service\MailerInterface;

class Mailer implements MailerInterface
{
    public function __construct(
        //private readonly MailManager $mailer
    )
    {
    }

    public function send(Mail $message): void
    {
        $mailerMessage = new Mailable();
        $mailerMessage->to($message->to()->toArray());
        $mailerMessage->subject($message->subject());
        $mailerMessage->html($message->body()->value());

        $sentMessage = LaravelMail::send($mailerMessage);
        //dd($sentMessage);
//        $this->logger->log($mail);
    }
}