<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\UseCase\Room;

use Module\Booking\Application\Admin\HotelBooking\Exception\NotFoundHotelRoomPriceException;
use Module\Booking\Deprecated\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\Exception\HotelBooking\NotFoundHotelRoomPrice;
use Module\Booking\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomPriceDayPartCollection;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomPriceItem;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class SetManualPrice implements UseCaseInterface
{
    public function __construct(
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
    ) {}

    public function execute(
        int $bookingId,
        int $roomBookingId,
        float|null $supplierDayPrice,
        float|null $clientDayPrice,
    ): void {
        $roomBooking = $this->roomBookingRepository->find(new RoomBookingId($roomBookingId));
        if ($roomBooking === null) {
            throw new EntityNotFoundException('Room booking not found');
        }
        $prices = new RoomPrices(
            supplierPrice: new RoomPriceItem(
                //@todo как тут собрать объект?
                new RoomPriceDayPartCollection(),
                $supplierDayPrice,
            ),
            clientPrice: new RoomPriceItem(
                new RoomPriceDayPartCollection(),
                $clientDayPrice,
            )
        );
        $roomBooking->updatePrices($prices);
        $this->roomBookingRepository->store($roomBooking);
        //@todo кинуть ивент на пересчет цен


        try {
            //@todo где загружается цена сейчас? что должно происходить если её нет?
        } catch (NotFoundHotelRoomPrice $e) {
            throw new NotFoundHotelRoomPriceException($e);
        }
    }
}
