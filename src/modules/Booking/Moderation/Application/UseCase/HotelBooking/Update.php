<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking;

use Carbon\CarbonPeriod;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\Entity\Details\HotelBooking;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\HotelBooking\BookingPeriod;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
    ) {
    }

    public function execute(int $bookingId, CarbonPeriod $period, string|null $note): void
    {
        $booking = $this->bookingUnitOfWork->findOrFail(new BookingId($bookingId));
        $booking->setNote($note);

        $bookingDetails = $this->bookingUnitOfWork->getDetails($booking->id());
        assert($bookingDetails instanceof HotelBooking);

        $bookingDetails->setBookingPeriod(BookingPeriod::fromCarbon($period));

        $this->bookingUnitOfWork->commit();
    }
}
