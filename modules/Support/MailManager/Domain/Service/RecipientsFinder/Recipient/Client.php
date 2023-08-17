<?php

declare(strict_types=1);

namespace Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient;

final class Client implements RecipientInterface
{
    public function __construct(public readonly int $id)
    {
    }
}