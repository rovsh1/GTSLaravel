<?php

namespace Module\Booking\Moderation\Application\Service;

use Module\Booking\Moderation\Application\RequestDto\AddAccommodationRequestDto;
use Module\Booking\Moderation\Application\RequestDto\UpdateRoomRequestDto;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationDetails;
use Sdk\Booking\ValueObject\HotelBooking\Condition;
use Sdk\Booking\ValueObject\HotelBooking\RoomInfo;
use Sdk\Booking\ValueObject\HotelBooking\RoomPrices;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;
use Sdk\Shared\ValueObject\Percent;
use Sdk\Shared\ValueObject\TimePeriod;

class AccommodationFactory
{
    private $data;

    public function __construct(
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
    ) {}

    public function fromRequest(AddAccommodationRequestDto|UpdateRoomRequestDto $requestDto): void
    {
        $this->data = $requestDto;
    }

    public function buildDetails(): AccommodationDetails
    {
        $earlyCheckIn = $this->data->earlyCheckIn !== null
            ? $this->buildMarkupCondition($this->data->earlyCheckIn) : null;
        $lateCheckOut = $this->data->lateCheckOut !== null
            ? $this->buildMarkupCondition($this->data->lateCheckOut) : null;

        return new AccommodationDetails(
            rateId: $this->data->rateId,
            isResident: $this->data->isResident,
            earlyCheckIn: $earlyCheckIn,
            lateCheckOut: $lateCheckOut,
            guestNote: $this->data->note,
            discount: new Percent(0),
        );
    }

    public function create(BookingId $bookingId): HotelAccommodation
    {
        return $this->accommodationRepository->create(
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
