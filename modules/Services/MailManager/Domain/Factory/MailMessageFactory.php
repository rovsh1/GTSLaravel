<?php

namespace Module\Services\MailManager\Domain\Factory;

use Module\Services\MailManager\Domain\Entity\Mail;
use Module\Services\MailManager\Domain\ValueObject\AddressList;
use Module\Services\MailManager\Domain\ValueObject\EmailAddress;
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
        $json = json_decode($payload);

        return new Mail(
            self::addressListFactory($json->to ?? []),
            $json->subject ?? '',
            new MailBody($json->body ?? ''),
        );
//        self::addressListFactory($json->from ?? []),
//            self::addressListFactory($json->replyTo ?? []),
//            self::addressListFactory($json->cc ?? []),
//            self::addressListFactory($json->bcc ?? []),
    }

    private static function addressListFactory(array|string|null $value): AddressList
    {
        if (is_string($value)) {
            return new AddressList([new EmailAddress($value)]);
        } elseif (is_array($value)) {
            return new AddressList($value);
        } else {
            return new AddressList();
        }
    }
}