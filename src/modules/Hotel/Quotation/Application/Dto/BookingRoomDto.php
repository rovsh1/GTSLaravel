<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\Dto;

final class BookingRoomDto
{
    public function __construct(
        public readonly int $roomId,
        public readonly int $count,
    ) {
    }
}