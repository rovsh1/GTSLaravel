<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin\Room;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\BookingCalculator;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\RoomPriceEditor;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetManualPrice implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
        private readonly RoomPriceEditor $roomPriceEditor,
        private readonly BookingUpdater $bookingUpdater,
        private readonly BookingCalculator $bookingCalculator,
    ) {}

    public function execute(int $bookingId, int $roomBookingId, float|null $boPrice, float|null $hoPrice): void
    {
        $roomBooking = $this->roomBookingRepository->find($roomBookingId);
        if ($boPrice === null && $hoPrice === null) {
            $roomBooking->setCalculatedPrices($this->roomPriceEditor);
        }
        if ($hoPrice !== null) {
            $roomBooking->setHoDayPrice($hoPrice, $this->roomPriceEditor);
        }
        if ($boPrice !== null) {
            $roomBooking->setBoDayPrice($boPrice, $this->roomPriceEditor);
        }
        $this->roomBookingRepository->store($roomBooking);

        $booking = $this->bookingRepository->find($bookingId);
        $booking->recalculatePrices($this->bookingCalculator);
        $this->bookingUpdater->store($booking);
    }
}