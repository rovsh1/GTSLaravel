<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Service\HotelBooking\RoomUpdater\Validator;

use Module\Booking\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Domain\Booking\Exception\HotelBooking\NotFoundHotelRoomPrice;
use Module\Booking\Domain\Booking\Service\HotelBooking\RoomUpdater\UpdateDataHelper;

class ExistRoomPriceValidator implements ValidatorInterface
{
    public function __construct(
        private readonly HotelAdapterInterface $hotelAdapter,
    ) {}

    public function validate(UpdateDataHelper $dataHelper): void
    {
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
    }
}
