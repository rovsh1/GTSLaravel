<?php

namespace Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient;

interface RecipientInterface
{
    public static function key(): string;

    public function id(): ?string;
}