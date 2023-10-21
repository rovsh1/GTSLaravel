<?php

declare(strict_types=1);

namespace Module\Booking\Application\AirportBooking\UseCase\Admin\Guest;

use Module\Booking\Application\AirportBooking\Exception\NotFoundServicePriceException;
use Module\Booking\Deprecated\AirportBooking\Service\GuestManager\GuestManager;
use Module\Booking\Domain\Booking\Exception\NotFoundAirportServicePrice;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\GuestId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Bind implements UseCaseInterface
{
    public function __construct(
        private readonly GuestManager $guestManager
    ) {}

    public function execute(int $bookingId, int $guestId): void
    {
        try {
            $this->guestManager->bind(
                new BookingId($bookingId),
                new GuestId($guestId)
            );
        } catch (NotFoundAirportServicePrice $e) {
            throw new NotFoundServicePriceException($e);
        }
    }
}
