<?php

namespace Module\Generic\Notification\Domain\MailSettings\ValueObject\Recipient;

use Module\Generic\Notification\Domain\MailSettings\ValueObject\RecipientTypeEnum;

final class ClientManagers implements RecipientInterface
{
    public function type(): RecipientTypeEnum
    {
        return RecipientTypeEnum::CLIENT_MANAGERS;
    }

    public function toArray(): array
    {
        return [];
    }

    public function isEqual(RecipientInterface $recipient): bool
    {
        return $recipient instanceof $this;
    }
}