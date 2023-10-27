<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\UseCase;

use Carbon\CarbonPeriod;
use Module\Booking\Domain\Booking\Event\HotelBooking\BookingPeriodChanged;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\BookingPeriod;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly HotelBookingRepositoryInterface $repository,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly BookingUpdater $bookingUpdater,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $bookingId, CarbonPeriod $period, string|null $note): void
    {
        $id = new BookingId($bookingId);
        $booking = $this->bookingRepository->findOrFail($id);
        if ($booking->note() !== $note) {
            $booking->setNote($note);
            $this->bookingUpdater->store($booking);
        }

        $bookingDetails = $this->repository->findOrFail($id);
        $newBookingPeriod = BookingPeriod::fromCarbon($period);
        if (!$bookingDetails->bookingPeriod()->isEqual($newBookingPeriod)) {
            $bookingDetails->setBookingPeriod($newBookingPeriod);
            $this->repository->store($bookingDetails);
            $this->eventDispatcher->dispatch(new BookingPeriodChanged($booking));
        }
    }
}
