<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin\Room;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Application\Request\UpdateRoomDto;
use Module\Booking\HotelBooking\Domain\Adapter\HotelRoomAdapterInterface;
use Module\Booking\HotelBooking\Domain\Entity\RoomBooking;
use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\RoomUpdater\RoomUpdater;
use Module\Booking\HotelBooking\Domain\Service\RoomUpdater\UpdateDataHelper;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\Condition;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Hotel\Application\Response\RoomDto;
use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\TimePeriod;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly RoomUpdater $roomUpdater,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository
    ) {}

    public function execute(UpdateRoomDto $request): void
    {
        $currentRoom = $this->roomBookingRepository->find($request->roomBookingId);
        if ($currentRoom === null) {
            throw new EntityNotFoundException('Room booking not found');
        }
        $hotelRoomDto = $this->hotelRoomAdapter->findById($request->roomId);
        $updateRoomDto = $this->buildUpdateRoomDataHelper($request, $currentRoom, $hotelRoomDto);
        $this->roomUpdater->update($currentRoom->id(), $updateRoomDto);
    }

    private function buildUpdateRoomDataHelper(UpdateRoomDto $request, RoomBooking $roomBooking, RoomDto $hotelRoomDto): UpdateDataHelper {
        $earlyCheckIn = $request->earlyCheckIn !== null ? $this->buildMarkupCondition($request->earlyCheckIn) : null;
        $lateCheckOut = $request->lateCheckOut !== null ? $this->buildMarkupCondition($request->lateCheckOut) : null;

        return new UpdateDataHelper(
            bookingId: new BookingId($request->bookingId),
            status: RoomBookingStatusEnum::from($request->status),
            roomInfo: new RoomInfo(
                $hotelRoomDto->id,
                $hotelRoomDto->name,
            ),
            guests: $roomBooking->guests(),
            details: new RoomBookingDetails(
                rateId: $request->rateId,
                isResident: $request->isResident,
                guestNote: $request->note,
                earlyCheckIn: $earlyCheckIn,
                lateCheckOut: $lateCheckOut,
                discount: new Percent($request->discount ?? 0),
            ),
            price: $roomBooking->price(),
        );
    }

    private function buildMarkupCondition(array $data): Condition
    {
        return new Condition(
            new TimePeriod($data['from'], $data['to']),
            new Percent($data['percent'])
        );
    }
}
