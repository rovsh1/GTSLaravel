<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin\Room;

use Module\Booking\Hotel\Application\Request\UpdateRoomDto;
use Module\Booking\Hotel\Domain\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Hotel\Domain\Entity\Booking;
use Module\Booking\Hotel\Domain\Entity\RoomBooking;
use Module\Booking\Hotel\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Hotel\Domain\ValueObject\Details\Condition;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\Hotel\Domain\ValueObject\RoomPrice;
use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\RoomCalculator;
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
        private readonly RoomCalculator $priceCalculator,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(UpdateRoomDto $request): void
    {
        $booking = $this->repository->find($request->bookingId);
        $currentRoom = $this->roomBookingRepository->find($request->roomBookingId);
        $guests = $currentRoom->guests();

        $hotelRoomDto = $this->hotelRoomAdapter->findById($request->roomId);
        $expectedGuestCount = $guests->count();
        if ($expectedGuestCount > $hotelRoomDto->guestsCount) {
            $guests = $guests->slice(0, $hotelRoomDto->guestsCount);
        }
        $earlyCheckIn = $request->earlyCheckIn !== null ? $this->buildMarkupCondition($request->earlyCheckIn) : null;
        $lateCheckOut = $request->lateCheckOut !== null ? $this->buildMarkupCondition($request->lateCheckOut) : null;
        $roomPrice = $this->buildPrice(
            $booking,
            $hotelRoomDto->id,
            $request->rateId,
            $guests->count(),
            $request->isResident,
            $earlyCheckIn,
            $lateCheckOut
        );

        $roomBooking = new RoomBooking(
            id: $currentRoom->id(),
            orderId: $booking->orderId(),
            bookingId: new Id($request->bookingId),
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
            price: $roomPrice,
        );

        $this->roomBookingRepository->store($roomBooking);
        $booking->updateRoomBooking($request->roomBookingId, $roomBooking);
        $this->eventDispatcher->dispatch(...$booking->pullEvents());
    }

    private function buildPrice(
        Booking $booking,
        int $roomId,
        int $rateId,
        int $guestsCount,
        bool $isResident,
        ?Condition $earlyCheckIn,
        ?Condition $lateCheckOut
    ): RoomPrice {
        return $this->priceCalculator->calculateByBooking(
            $booking,
            $roomId,
            $rateId,
            $isResident,
            $guestsCount,
            $earlyCheckIn?->priceMarkup()->value(),
            $lateCheckOut?->priceMarkup()->value()
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
