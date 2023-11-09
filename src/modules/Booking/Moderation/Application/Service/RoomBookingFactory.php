<?php

namespace Module\Booking\Moderation\Application\Service;

use Module\Booking\Moderation\Application\RequestDto\AddRoomRequestDto;
use Module\Booking\Moderation\Application\RequestDto\UpdateRoomRequestDto;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Entity\HotelRoomBooking;
use Module\Booking\Shared\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\Condition;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomBookingDetails;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomInfo;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Module\Shared\ValueObject\Percent;
use Module\Shared\ValueObject\TimePeriod;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class RoomBookingFactory
{
    private $data;

    public function __construct(
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
    ) {
    }

    public function fromRequest(AddRoomRequestDto|UpdateRoomRequestDto $requestDto): void
    {
        $this->data = $requestDto;
    }

    public function buildDetails(): RoomBookingDetails
    {
        $earlyCheckIn = $this->data->earlyCheckIn !== null
            ? $this->buildMarkupCondition($this->data->earlyCheckIn) : null;
        $lateCheckOut = $this->data->lateCheckOut !== null
            ? $this->buildMarkupCondition($this->data->lateCheckOut) : null;

        return new RoomBookingDetails(
            rateId: $this->data->rateId,
            isResident: $this->data->isResident,
            earlyCheckIn: $earlyCheckIn,
            lateCheckOut: $lateCheckOut,
            guestNote: $this->data->note,
            discount: new Percent(0),
        );
    }

    public function create(BookingId $bookingId): HotelRoomBooking
    {
        return $this->roomBookingRepository->create(
            $bookingId,
            $this->buildRoomInfo(),
            $this->buildDetails(),
            RoomPrices::buildEmpty()
        );
    }

    private function buildRoomInfo(): RoomInfo
    {
        $hotelRoomDto = $this->hotelRoomAdapter->findById($this->data->roomId);
        if ($hotelRoomDto === null) {
            throw new EntityNotFoundException('Hotel room not found');
        }

        return new RoomInfo(
            $hotelRoomDto->id,
            $hotelRoomDto->name,
            $hotelRoomDto->guestsCount,
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