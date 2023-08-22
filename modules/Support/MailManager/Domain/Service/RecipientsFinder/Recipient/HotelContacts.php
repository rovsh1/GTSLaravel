<?php

declare(strict_types=1);

namespace Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient;

final class HotelContacts implements RecipientInterface
{
    public function __construct(private readonly int $hotelId)
    {
    }

    public static function key(): string
    {
        return 'client-contacts';
    }

    public function id(): ?string
    {
        return (string)$this->hotelId;
    }
}