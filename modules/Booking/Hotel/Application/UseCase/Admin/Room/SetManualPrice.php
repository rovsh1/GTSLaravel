<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin\Room;

use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Hotel\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Hotel\Domain\ValueObject\ManualChangablePrice;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetManualPrice implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository
    ) {}

    public function execute(int $bookingId, int $roomBookingId, float|null $boPrice, float|null $hoPrice): void
    {
        $booking = $this->bookingRepository->find($bookingId);
        $roomBooking = $this->roomBookingRepository->find($roomBookingId);
        $roomBooking->setBoPriceValue(new ManualChangablePrice($boPrice, true));
    }
}
