<?php

declare(strict_types=1);

namespace Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient;

final class Administrators implements RecipientInterface
{
    public static function key(): string
    {
        return 'administrators';
    }

    public function id(): ?string
    {
        return null;
    }
}