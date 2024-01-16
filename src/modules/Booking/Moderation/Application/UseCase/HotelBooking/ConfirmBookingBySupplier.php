<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking;

use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class ConfirmBookingBySupplier implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork
    ) {}

    public function execute(int $bookingId): void
    {
        $booking = $this->bookingUnitOfWork->findOrFail(new BookingId($bookingId));
        $this->bookingUnitOfWork->persist($booking);
        $booking->confirm();
        $this->bookingUnitOfWork->commit();
    }
}
