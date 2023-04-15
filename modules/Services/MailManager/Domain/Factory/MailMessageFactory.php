<?php

namespace Module\Services\MailManager\Domain\Factory;

use Module\Services\MailManager\Domain\Entity\Mail;
use Module\Services\MailManager\Domain\ValueObject\AddressList;
use Module\Services\MailManager\Domain\ValueObject\MailBody;

class MailMessageFactory
{
    public static function fromDto(mixed $mailDto): Mail
    {
        return new Mail(
            new AddressList($mailDto->to),
            $mailDto->subject,
            new MailBody($mailDto->body),
        );
    }

    public static function deserialize(string $payload): Mail
    {
        $deserialized = unserialize($payload);
        return new Mail(
            new AddressList(),
            '',
            new MailBody(''),
        );
    }
}