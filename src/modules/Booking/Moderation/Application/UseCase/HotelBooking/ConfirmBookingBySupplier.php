<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking;

use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class ConfirmBookingBySupplier implements UseCaseInterface
{
    public function execute(int $bookingId): void {}
}
