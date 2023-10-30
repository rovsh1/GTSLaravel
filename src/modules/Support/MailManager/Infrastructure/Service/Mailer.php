<?php

namespace Module\Support\MailManager\Infrastructure\Service;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail as LaravelMail;
use Module\Support\MailManager\Domain\Entity\Mail;
use Module\Support\MailManager\Domain\Service\MailerInterface;

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
//        $this->logger->log($mail);
    }
}