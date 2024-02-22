<?php

namespace Pkg\MailManager\Factory;

use Pkg\MailManager\Mail;
use Pkg\MailManager\ValueObject\AddressList;
use Pkg\MailManager\ValueObject\Attachments;
use Pkg\MailManager\ValueObject\MailBody;
use Pkg\MailManager\ValueObject\MailId;
use Pkg\MailManager\ValueObject\QueueMailStatusEnum;
use Sdk\Shared\Dto\Mail\AttachmentDto;
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
            : new Attachments(array_map(fn(AttachmentDto $fileDto) => new File($fileDto->guid), $mailDto->attachments));
    }
}