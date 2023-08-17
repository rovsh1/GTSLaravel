<?php

declare(strict_types=1);

namespace Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient;

final class HotelEmployees implements RecipientInterface
{
    public function __construct(public readonly int $hotelId)
    {
    }
}