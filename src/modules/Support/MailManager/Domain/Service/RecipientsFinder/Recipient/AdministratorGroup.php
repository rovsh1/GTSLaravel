<?php

declare(strict_types=1);

namespace Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient;

final class AdministratorGroup implements RecipientInterface
{
    public function __construct(private readonly int $id)
    {
    }

    public static function key(): string
    {
        return 'administrator-group';
    }

    public function id(): ?string
    {
        return (string)$this->id;
    }
}