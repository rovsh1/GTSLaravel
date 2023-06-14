<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin\Room;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\Hotel\Application\Request\UpdateRoomDto;
use Module\Booking\Hotel\Domain\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Hotel\Domain\ValueObject\Details\Condition;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository;
use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\TimePeriod;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly BookingUpdater $bookingUpdater,
    ) {}

    public function execute(UpdateRoomDto $request): void
    {
        $booking = $this->repository->find($request->bookingId);
        $currentRoom = $booking->roomBookings()->get($request->roomIndex);
        $guests = $currentRoom->guests();

        $hotelRoomDto = $this->hotelRoomAdapter->findById($request->roomId);
        $expectedGuestCount = $guests->count();
        if ($expectedGuestCount > $hotelRoomDto->guestsNumber) {
            $guests = $guests->slice(0, $hotelRoomDto->guestsNumber);
        }
        $booking->updateRoomBooking(
            $request->roomIndex,
            new RoomBooking(
                status: RoomBookingStatusEnum::from($request->status),
                roomInfo: new RoomInfo(
                    $hotelRoomDto->id,
                    $hotelRoomDto->name,
                ),
                guests: $guests,
                details: new RoomBookingDetails(
                    rateId: $request->rateId,
                    isResident: $request->isResident,
                    guestNote: $request->note,
                    roomCount: $request->roomCount,
                    earlyCheckIn: $request->earlyCheckIn !== null ? $this->buildMarkupCondition(
                        $request->earlyCheckIn
                    ) : null,
                    lateCheckOut: $request->lateCheckOut !== null ? $this->buildMarkupCondition(
                        $request->lateCheckOut
                    ) : null,
                    discount: new Percent($request->discount ?? 0),
                ),
            )
        );
        $this->bookingUpdater->store($booking);
    }

    private function buildMarkupCondition(array $data): Condition
    {
        return new Condition(
            new TimePeriod($data['from'], $data['to']),
            new Percent($data['percent'])
        );
    }
}
