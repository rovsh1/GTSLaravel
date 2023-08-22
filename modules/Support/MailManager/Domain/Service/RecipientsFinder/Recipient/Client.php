<?php

declare(strict_types=1);

namespace Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient;

final class Client implements RecipientInterface
{
    public function __construct(private readonly int $id)
    {
    }

    public static function key(): string
    {
        return 'client';
    }

    public function id(): ?string
    {
        return (string)$this->id;
    }
}