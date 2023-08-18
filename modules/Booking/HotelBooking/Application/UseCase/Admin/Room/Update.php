<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin\Room;

use Module\Booking\HotelBooking\Application\Factory\RoomUpdaterDataHelperFactory;
use Module\Booking\HotelBooking\Application\Request\UpdateRoomDto;
use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\RoomUpdater\RoomUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly RoomUpdater $roomUpdater,
        private readonly RoomUpdaterDataHelperFactory $dataHelperFactory,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository
    ) {}

    public function execute(UpdateRoomDto $request): void
    {
        $roomBooking = $this->roomBookingRepository->find($request->roomBookingId);
        if ($roomBooking === null) {
            throw new EntityNotFoundException('Room booking not found');
        }
        $updateRoomDto = $this->dataHelperFactory->build($request, $roomBooking->guests(), $roomBooking->price());
        $this->roomUpdater->update($roomBooking->id(), $updateRoomDto);
    }
}
