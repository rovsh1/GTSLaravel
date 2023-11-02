<?php

namespace Module\Generic\Notification\Domain\MailSettings\ValueObject\Recipient;

use Module\Generic\Notification\Domain\MailSettings\ValueObject\RecipientTypeEnum;

interface RecipientInterface
{
    public function type(): RecipientTypeEnum;

    public function isEqual(RecipientInterface $recipient): bool;

    public function toArray(): array;
}