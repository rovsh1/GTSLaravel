<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Service\HotelBooking\RoomUpdater\Validator;

use Module\Booking\Shared\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Exception\HotelBooking\NotFoundHotelRoomPrice;
use Module\Booking\Shared\Domain\Booking\Service\HotelBooking\RoomUpdater\UpdateDataHelper;

class ExistRoomPriceValidator implements ValidatorInterface
{
    public function __construct(
        private readonly HotelAdapterInterface $hotelAdapter,
    ) {
    }

    public function validate(UpdateDataHelper $dataHelper): void
    {
        try {
            foreach ($dataHelper->bookingDetails->bookingPeriod()->dates() as $date) {
                $price = $this->hotelAdapter->getRoomPrice(
                    roomId: $dataHelper->roomInfo->id(),
                    rateId: $dataHelper->roomDetails->rateId(),
                    isResident: $dataHelper->roomDetails->isResident(),
                    guestsCount: 1,//@todo как тут правильно?
                    date: $date
                );
                if ($price === null) {
                    throw new NotFoundHotelRoomPrice('Room price not found.');
                }
            }
        } catch (\Throwable $e) {
            throw new NotFoundHotelRoomPrice('Room price not found.');
        }
    }
}
