<?php

namespace Module\Support\MailManager\Application\Factory;

use Module\Support\MailManager\Domain\Entity\Mail;
use Module\Support\MailManager\Domain\ValueObject\AddressList;
use Module\Support\MailManager\Domain\ValueObject\Attachments;
use Module\Support\MailManager\Domain\ValueObject\MailBody;
use Module\Support\MailManager\Domain\ValueObject\MailId;
use Module\Support\MailManager\Domain\ValueObject\QueueMailStatusEnum;
use Sdk\Shared\Dto\FileDto;
use Sdk\Shared\Dto\Mail\MailMessageDto;
use Sdk\Shared\ValueObject\File;

class MailMessageFactory
{
    public static function fromDto(MailMessageDto $mailDto): Mail
    {
        return new Mail(
            id: MailId::createNew(),
            status: QueueMailStatusEnum::WAITING,
            to: new AddressList($mailDto->to),
            subject: $mailDto->subject,
            body: new MailBody($mailDto->body),
            attachments: self::buildAttachments($mailDto)
        );
    }

    private static function buildAttachments(MailMessageDto $mailDto): Attachments
    {
        return empty($mailDto->attachments)
            ? new Attachments([])
            : new Attachments(array_map(fn(FileDto $fileDto) => new File($fileDto->guid), $mailDto->attachments));
    }
}