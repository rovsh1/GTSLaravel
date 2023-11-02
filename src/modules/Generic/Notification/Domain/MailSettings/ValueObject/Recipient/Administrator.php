<?php

declare(strict_types=1);

namespace Module\Generic\Notification\Domain\MailSettings\ValueObject\Recipient;

use Module\Generic\Notification\Domain\MailSettings\ValueObject\RecipientTypeEnum;

final class Administrator implements RecipientInterface
{
    public function __construct(
        private readonly int $administratorId
    ) {
    }

    public function administratorId(): int
    {
        return $this->administratorId;
    }

    public function type(): RecipientTypeEnum
    {
        return RecipientTypeEnum::ADMINISTRATOR;
    }

    public function toArray(): array
    {
        return [
            'administratorId' => $this->administratorId
        ];
    }

    public function isEqual(RecipientInterface $recipient): bool
    {
        return $recipient instanceof $this
            && $recipient->administratorId === $this->administratorId;
    }
}