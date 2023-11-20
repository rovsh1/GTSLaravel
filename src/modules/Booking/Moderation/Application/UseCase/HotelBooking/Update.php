<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking;

use Carbon\CarbonPeriod;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\BookingPeriod;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
    ) {
    }

    public function execute(int $bookingId, CarbonPeriod $period, string|null $note): void
    {
        $booking = $this->bookingUnitOfWork->bookingRepository()->findOrFail(new BookingId($bookingId));
        $booking->setNote($note);

        $bookingDetails = $this->bookingUnitOfWork->detailsRepository()->findOrFail($booking->id());
        assert($bookingDetails instanceof HotelBooking);

        $bookingDetails->setBookingPeriod(BookingPeriod::fromCarbon($period));

        $this->bookingUnitOfWork->commit();
    }
}
