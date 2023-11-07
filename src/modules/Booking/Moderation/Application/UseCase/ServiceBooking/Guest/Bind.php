<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking\Guest;

use Module\Booking\Moderation\Application\Exception\NotFoundServicePriceException;
use Module\Booking\Shared\Domain\Booking\Exception\NotFoundAirportServicePrice;
use Module\Booking\Shared\Domain\Booking\Service\AirportBooking\GuestManager;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Guest\ValueObject\GuestId;
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
