<?php

namespace Module\Booking\Moderation\Application\Service;

use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestId;

class GuestValidator
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly GuestRepositoryInterface $guestRepository,
    ) {}

    public function ensureCanBindToBooking(int $bookingId, int $guestId): void
    {
        $booking = $this->bookingRepository->findOrFail(new BookingId($bookingId));
        $guest = $this->guestRepository->findOrFail(new GuestId($guestId));
        if (!$guest->orderId()->isEqual($booking->orderId())) {
            throw new \Exception('Guest order failed');
        }
    }
}