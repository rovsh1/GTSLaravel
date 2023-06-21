<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin\Room;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\Hotel\Application\Request\AddRoomDto;
use Module\Booking\Hotel\Domain\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Hotel\Domain\Entity\Booking;
use Module\Booking\Hotel\Domain\ValueObject\Details\Condition;
use Module\Booking\Hotel\Domain\ValueObject\Details\GuestCollection;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\Hotel\Domain\ValueObject\RoomPrice;
use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\RoomCalculator;
use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\TimePeriod;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Add implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly BookingUpdater $bookingUpdater,
        private readonly RoomCalculator $priceCalculator
    ) {
    }

    public function execute(AddRoomDto $request): void
    {
        $booking = $this->repository->find($request->bookingId);
        $hotelRoomDto = $this->hotelRoomAdapter->findById($request->roomId);
        $earlyCheckIn = $request->earlyCheckIn !== null ? $this->buildMarkupCondition($request->earlyCheckIn) : null;
        $lateCheckOut = $request->lateCheckOut !== null ? $this->buildMarkupCondition($request->lateCheckOut) : null;
        $roomPrice = $this->buildPrice($booking, $hotelRoomDto->id, $request, $earlyCheckIn, $lateCheckOut);
        $booking->addRoomBooking(
            new RoomBooking(
                status: RoomBookingStatusEnum::from($request->status),
                roomInfo: new RoomInfo(
                    $hotelRoomDto->id,
                    $hotelRoomDto->name,
                ),
                guests: new GuestCollection(),
                details: new RoomBookingDetails(
                    rateId: $request->rateId,
                    isResident: $request->isResident,
                    guestNote: $request->note,
                    earlyCheckIn: $earlyCheckIn,
                    lateCheckOut: $lateCheckOut,
                    discount: new Percent($request->discount ?? 0),
                ),
            )
        );
        $this->bookingUpdater->store($booking);
    }

    private function buildPrice(
        Booking $booking,
        int $roomId,
        AddRoomDto $request,
        ?Condition $earlyCheckIn,
        ?Condition $lateCheckOut
    ): RoomPrice {
        return $this->priceCalculator->calculateByBooking(
            $booking,
            $roomId,
            $request->isResident,
            $guestsCount = 1,
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
