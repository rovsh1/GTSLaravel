<?php

declare(strict_types=1);

namespace Module\Booking\Deprecated\HotelBooking\Service\RoomUpdater\Validator;

use Module\Booking\Deprecated\HotelBooking\Exception\NotFoundHotelRoomPrice;
use Module\Booking\Deprecated\HotelBooking\Service\RoomUpdater\UpdateDataHelper;
use Module\Booking\Domain\Booking\Adapter\HotelAdapterInterface;

class ExistRoomPriceValidator implements ValidatorInterface
{
    public function __construct(
        private readonly HotelAdapterInterface $hotelAdapter,
    ) {}

    public function validate(UpdateDataHelper $dataHelper): void
    {
        return;

        foreach ($dataHelper->booking->period()->dates() as $date) {

            $price = $this->hotelAdapter->getRoomPrice(
                roomId: $dataHelper->roomInfo->id(),
                rateId: $dataHelper->details->rateId(),
                isResident: $dataHelper->details->isResident(),
                guestsCount: 1,//@todo как тут правильно?
                date: $date
            );
            if ($price === null) {
                throw new NotFoundHotelRoomPrice('Room price not found.');
            }
        }
    }
}
