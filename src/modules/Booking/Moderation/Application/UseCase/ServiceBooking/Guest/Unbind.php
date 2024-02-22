<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking\Guest;

use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\Contracts\Entity\AirportDetailsInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Unbind implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork
    ) {}

    public function execute(int $bookingId, int $guestId): void
    {
        $details = $this->bookingUnitOfWork->getDetails(new BookingId($bookingId));
        assert($details instanceof AirportDetailsInterface);
        $details->removeGuest(new GuestId($guestId));
        $this->bookingUnitOfWork->commit();
    }
}
