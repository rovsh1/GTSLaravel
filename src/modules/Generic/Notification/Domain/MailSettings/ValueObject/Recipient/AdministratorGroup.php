<?php

namespace Module\Generic\Notification\Domain\MailSettings\ValueObject\Recipient;

use Module\Generic\Notification\Domain\MailSettings\ValueObject\RecipientTypeEnum;

final class AdministratorGroup implements RecipientInterface
{
    public function __construct(
        private readonly int $groupId
    ) {
    }

    public function groupId(): string
    {
        return $this->groupId;
    }

    public function type(): RecipientTypeEnum
    {
        return RecipientTypeEnum::ADMINISTRATOR_GROUP;
    }

    public function toArray(): array
    {
        return [
            'groupId' => $this->groupId
        ];
    }

    public function isEqual(RecipientInterface $recipient): bool
    {
        return $recipient instanceof $this
            && $recipient->groupId === $this->groupId;
    }
}