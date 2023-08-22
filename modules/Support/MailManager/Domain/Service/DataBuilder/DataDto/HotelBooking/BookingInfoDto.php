<?php

namespace Module\Support\MailManager\Domain\Service\DataBuilder\DataDto\HotelBooking;

use DateTimeInterface;

final class BookingInfoDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $url,
        public readonly DateTimeInterface $dateCheckin,
        public readonly DateTimeInterface $dateCheckout,
        public readonly int $nightsNumber,
        public readonly float $priceNet,
        public readonly string $status,
        public readonly ?string $note,
        public readonly DateTimeInterface $createdAt
    ) {
    }
}