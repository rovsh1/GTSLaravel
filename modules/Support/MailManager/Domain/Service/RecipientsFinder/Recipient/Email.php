<?php

declare(strict_types=1);

namespace Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient;

final class Email implements RecipientInterface
{
    public function __construct(private readonly string $address)
    {
    }

    public static function key(): string
    {
        return 'email';
    }

    public function id(): ?string
    {
        return $this->address;
    }
}