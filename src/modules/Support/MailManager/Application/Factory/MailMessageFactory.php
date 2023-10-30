<?php

namespace Module\Support\MailManager\Application\Factory;

use Module\Support\MailManager\Domain\Entity\Mail;
use Module\Support\MailManager\Domain\ValueObject\AddressList;
use Module\Support\MailManager\Domain\ValueObject\MailBody;
use Module\Support\MailManager\Domain\ValueObject\MailId;
use Module\Support\MailManager\Domain\ValueObject\QueueMailStatusEnum;

class MailMessageFactory
{
    public static function fromDto(mixed $mailDto): Mail
    {
        return new Mail(
            MailId::createNew(),
            QueueMailStatusEnum::WAITING,
            new AddressList($mailDto->to),
            $mailDto->subject,
            new MailBody($mailDto->body),
        );
    }
}