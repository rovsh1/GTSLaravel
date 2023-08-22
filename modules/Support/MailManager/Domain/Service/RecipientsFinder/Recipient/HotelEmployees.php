<?php

declare(strict_types=1);

namespace Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient;

final class HotelEmployees implements RecipientInterface
{
    public function __construct(private readonly int $hotelId)
    {
    }

    public static function key(): string
    {
        return 'hotel-employees';
    }

    public function id(): ?string
    {
        return (string)$this->hotelId;
    }
}