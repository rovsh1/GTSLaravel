<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin\Room;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Application\Request\UpdateRoomDto;
use Module\Booking\HotelBooking\Domain\Adapter\HotelRoomAdapterInterface;
use Module\Booking\HotelBooking\Domain\Entity\RoomBooking;
use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\RoomPriceEditor;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\Condition;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\HotelBooking\Infrastructure\Repository\BookingRepository;
use Module\Shared\Domain\ValueObject\Id;
use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\TimePeriod;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly RoomPriceEditor $roomPriceEditor,
    ) {}

    public function execute(UpdateRoomDto $request): void
    {
        $booking = $this->repository->find($request->bookingId);
        $currentRoom = $this->roomBookingRepository->find($request->roomBookingId);
        $guests = $currentRoom?->guests() ?? 0;

        $hotelRoomDto = $this->hotelRoomAdapter->findById($request->roomId);
        $expectedGuestCount = $guests?->count();
        if ($expectedGuestCount > $hotelRoomDto->guestsCount) {
            $guests = $guests->slice(0, $hotelRoomDto->guestsCount);
        }
        $earlyCheckIn = $request->earlyCheckIn !== null ? $this->buildMarkupCondition($request->earlyCheckIn) : null;
        $lateCheckOut = $request->lateCheckOut !== null ? $this->buildMarkupCondition($request->lateCheckOut) : null;

        $roomBooking = new RoomBooking(
            id: $currentRoom->id(),
            orderId: $booking->orderId(),
            bookingId: new BookingId($request->bookingId),
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
                earlyCheckIn: $earlyCheckIn,
                lateCheckOut: $lateCheckOut,
                discount: new Percent($request->discount ?? 0),
            ),
            price: $currentRoom->price(),
        );
        $roomBooking->recalculatePrices($this->roomPriceEditor);

        $this->roomBookingRepository->store($roomBooking);
        $booking->updateRoomBooking($request->roomBookingId, $roomBooking);
        $this->eventDispatcher->dispatch(...$booking->pullEvents());
    }

    private function buildMarkupCondition(array $data): Condition
    {
        return new Condition(
            new TimePeriod($data['from'], $data['to']),
            new Percent($data['percent'])
        );
    }
}
