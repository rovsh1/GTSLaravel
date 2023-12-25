<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Infrastructure\Service;

use Module\Booking\Requesting\Domain\Service\ChangesRegistratorInterface;
use Module\Booking\Requesting\Infrastructure\Models\Changes;
use Sdk\Booking\ValueObject\BookingId;

class ChangesRegistrator implements ChangesRegistratorInterface
{
    public function register(
        BookingId $bookingId,
        string $field,
        mixed $before,
        mixed $after
    ): void {
        Changes::create([
            'booking_id' => $bookingId->value(),
            'field' => $field,
            'before' => is_array($before) ? json_encode($before) : $before,
            'after' => is_array($after) ? json_encode($after) : $after,
        ]);
    }

    public function clear(BookingId $bookingId): void
    {
        Changes::where('booking_id', $bookingId->value())
            ->delete();
    }
}
