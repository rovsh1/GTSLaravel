<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin\Room;

use Module\Booking\HotelBooking\Application\Factory\RoomUpdaterDataHelperFactory;
use Module\Booking\HotelBooking\Application\Request\AddRoomDto;
use Module\Booking\HotelBooking\Domain\Service\RoomUpdater\RoomUpdater;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\GuestCollection;
use Module\Booking\HotelBooking\Domain\ValueObject\RoomPrice;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Add implements UseCaseInterface
{
    public function __construct(
        private readonly RoomUpdater $roomUpdater,
        private readonly RoomUpdaterDataHelperFactory $dataHelperFactory,
    ) {}

    public function execute(AddRoomDto $request): void
    {
        $addRoomDto = $this->dataHelperFactory->build($request, new GuestCollection(), RoomPrice::buildEmpty());
        $this->roomUpdater->add($addRoomDto);
    }
}
