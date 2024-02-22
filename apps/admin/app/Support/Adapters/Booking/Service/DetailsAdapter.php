<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking\Service;

use Module\Booking\Moderation\Application\UseCase\ServiceBooking\Guest\Bind;
use Module\Booking\Moderation\Application\UseCase\ServiceBooking\Guest\Unbind;
use Module\Booking\Moderation\Application\UseCase\ServiceBooking\UpdateDetailsField;

class DetailsAdapter
{
    public function updateDetailsField(int $bookingId, string $field, mixed $value): void
    {
        app(UpdateDetailsField::class)->execute($bookingId, $field, $value);
    }

    public function bindGuest(int $bookingId, int $guestId): void
    {
        app(Bind::class)->execute($bookingId, $guestId);
    }

    public function unbindGuest(int $bookingId, int $guestId): void
    {
        app(Unbind::class)->execute($bookingId, $guestId);
    }
}
