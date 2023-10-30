<?php

declare(strict_types=1);

namespace Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient;

final class HotelManagers implements RecipientInterface
{
    public function __construct(private readonly int $hotelId)
    {
    }

    public static function key(): string
    {
        return 'hotel-managers';
    }

    public function id(): ?string
    {
        return (string)$this->hotelId;
    }
}