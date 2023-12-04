<?php

namespace Module\Generic\Notification\Domain\Recipient;

use Module\Generic\Notification\Domain\Enum\RecipientTypeEnum;

final class EmailAddress implements RecipientInterface
{
    public function __construct(
        private readonly string $email,
        private readonly ?string $presentation,
    ) {
    }

    public function email(): string
    {
        return $this->email;
    }

    public function presentation(): string
    {
        return $this->presentation;
    }

    public function type(): RecipientTypeEnum
    {
        return RecipientTypeEnum::EMAIL_ADDRESS;
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'presentation' => $this->presentation,
        ];
    }

    public function isEqual(RecipientInterface $recipient): bool
    {
        return $recipient instanceof $this
            && $recipient->email === $this->email;
    }
}