<?php

namespace Support\MailManager\Factory;

use Sdk\Shared\Dto\Mail\AttachmentDto;
use Sdk\Shared\Dto\Mail\MailMessageDto;
use Sdk\Shared\ValueObject\File;
use Support\MailManager\Mail;
use Support\MailManager\ValueObject\AddressList;
use Support\MailManager\ValueObject\Attachments;
use Support\MailManager\ValueObject\MailBody;
use Support\MailManager\ValueObject\MailId;
use Support\MailManager\ValueObject\QueueMailStatusEnum;

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
            : new Attachments(array_map(fn(AttachmentDto $fileDto) => new File($fileDto->guid), $mailDto->attachments));
    }
}