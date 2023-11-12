<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking;

use Carbon\CarbonPeriod;
use Module\Booking\Moderation\Domain\Booking\Event\HotelBooking\BookingPeriodChanged;
use Module\Booking\Shared\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWork;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\BookingPeriod;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly HotelBookingRepositoryInterface $detailsRepository,
        private readonly BookingUnitOfWork $bookingUnitOfWork,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $bookingId, CarbonPeriod $period, string|null $note): void
    {
        $id = new BookingId($bookingId);
        $booking = $this->bookingUnitOfWork->findOrFail($id);
        if ($booking->note() !== $note) {
            $booking->setNote($note);
        }

        $bookingDetails = $this->detailsRepository->findOrFail($id);
        $newBookingPeriod = BookingPeriod::fromCarbon($period);
        if (!$bookingDetails->bookingPeriod()->isEqual($newBookingPeriod)) {
            $bookingDetails->setBookingPeriod($newBookingPeriod);
            $this->detailsRepository->store($bookingDetails);
            $this->eventDispatcher->dispatch(new BookingPeriodChanged($booking));
        }

        $this->bookingUnitOfWork->commit();
    }
}
