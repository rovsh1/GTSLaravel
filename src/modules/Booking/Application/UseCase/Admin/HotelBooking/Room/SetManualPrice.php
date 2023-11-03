<?php

declare(strict_types=1);

namespace Module\Booking\Application\UseCase\Admin\HotelBooking\Room;

use Module\Booking\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\Booking\Service\HotelBooking\PriceCalculator\PriceCalculator;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomPriceItem;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetManualPrice implements UseCaseInterface
{
    public function __construct(
        private readonly PriceCalculator $bookingPriceCalculator,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
    ) {}

    public function execute(
        int $bookingId,
        int $roomBookingId,
        float|null $supplierDayPrice,
        float|null $clientDayPrice,
    ): void {
        $roomBooking = $this->roomBookingRepository->findOrFail(new RoomBookingId($roomBookingId));
        $newPrices = new RoomPrices(
            supplierPrice: new RoomPriceItem(
                $roomBooking->prices()->supplierPrice()->dayParts(),
                $supplierDayPrice,
            ),
            clientPrice: new RoomPriceItem(
                $roomBooking->prices()->clientPrice()->dayParts(),
                $clientDayPrice,
            ),
        );
        $roomBooking->updatePrices($newPrices);
        $this->roomBookingRepository->store($roomBooking);
        $this->bookingPriceCalculator->calculateByBookingId(new BookingId($bookingId));
    }
}