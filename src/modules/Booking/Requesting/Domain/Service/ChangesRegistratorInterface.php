<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\Service;

use Sdk\Booking\ValueObject\BookingId;

interface ChangesRegistratorInterface
{
    public function register(
        BookingId $bookingId,
        string $field,
        mixed $before,
        mixed $after
    ): void;

    public function clear(BookingId $bookingId): void;
}
