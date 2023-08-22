<?php

namespace Module\Support\MailManager\Domain\Service\DataBuilder\DataDto\HotelBooking;

final class BookingRoomDto
{
    public function __construct(
        public readonly int $roomId,
        public readonly string $name,
        public readonly ?string $checkinTime,
        public readonly ?string $checkoutTime,
        public readonly string $guestsNames,
        public readonly float $priceNet,
        public readonly string $status,
        public readonly int $guestsNumber,
        public readonly ?string $note
    ) {
    }
}