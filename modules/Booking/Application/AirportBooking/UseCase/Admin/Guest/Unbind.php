<?php

declare(strict_types=1);

namespace Module\Booking\Application\AirportBooking\UseCase\Admin\Guest;

use Module\Booking\Domain\AirportBooking\Service\GuestManager\GuestManager;
use Module\Booking\Domain\Order\ValueObject\GuestId;
use Module\Booking\Domain\Shared\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Unbind implements UseCaseInterface
{
    public function __construct(
        private readonly GuestManager $guestManager
    ) {}

    public function execute(int $bookingId, int $guestId): void
    {
        $this->guestManager->unbind(
            new BookingId($bookingId),
            new GuestId($guestId)
        );
    }
}