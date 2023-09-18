<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\Factory;

use Module\Booking\Common\Application\Factory\AbstractBookingDtoFactory;
use Module\Booking\Common\Application\Service\StatusStorage;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\HotelBooking\Application\Dto\BookingDto;
use Module\Booking\HotelBooking\Application\Dto\Details\AdditionalInfoDto;
use Module\Booking\HotelBooking\Application\Dto\Details\BookingPeriodDto;
use Module\Booking\HotelBooking\Application\Dto\Details\CancelConditionsDto;
use Module\Booking\HotelBooking\Application\Dto\Details\HotelInfoDto;
use Module\Booking\HotelBooking\Application\Dto\Details\RoomBooking\RoomBookingDetailsDto;
use Module\Booking\HotelBooking\Application\Dto\Details\RoomBooking\RoomInfoDto;
use Module\Booking\HotelBooking\Application\Dto\Details\RoomBooking\RoomPriceDto;
use Module\Booking\HotelBooking\Application\Dto\Details\RoomBookingDto;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBookingCollection;
use Module\Booking\Order\Application\Response\GuestDto;
use Module\Booking\Order\Domain\Repository\GuestRepositoryInterface;

class BookingDtoFactory extends AbstractBookingDtoFactory
{
    public function __construct(
        StatusStorage $statusStorage,
        private readonly GuestRepositoryInterface $guestRepository,
        private readonly BookingPriceDtoFactory $bookingPriceDtoFactory,
    ) {
        parent::__construct($statusStorage);
    }

    public function createFromEntity(BookingInterface $booking): BookingDto
    {
        assert($booking instanceof Booking);

        return new BookingDto(
            $booking->id()->value(),
            $this->statusStorage->get($booking->status()),
            $booking->orderId()->value(),
            $booking->createdAt(),
            $booking->creatorId()->value(),
            $booking->note(),
            HotelInfoDto::fromDomain($booking->hotelInfo()),
            BookingPeriodDto::fromDomain($booking->period()),
            $booking->additionalInfo() !== null ? AdditionalInfoDto::fromDomain($booking->additionalInfo()) : null,
            $this->buildGuests($booking->roomBookings()),
            CancelConditionsDto::fromDomain($booking->cancelConditions()),
            $this->bookingPriceDtoFactory->createFromEntity($booking->price()),
            $booking->quotaProcessingMethod(),
        );
    }

    /**
     * @param RoomBookingCollection $roomBookings
     * @return array<int, RoomBookingDto>
     */
    private function buildGuests(RoomBookingCollection $roomBookings): array
    {
        $dtos = [];
        foreach ($roomBookings as $roomBooking) {
            //@todo Поместить туристов в заказы, и на фронте склеивать по id
            $guests = $this->guestRepository->get($roomBooking->guestIds());
            $dtos[] = new RoomBookingDto(
                id: $roomBooking->id()->value(),
                roomInfo: RoomInfoDto::fromDomain($roomBooking->roomInfo()),
                guests: GuestDto::collectionFromDomain($guests),
                details: RoomBookingDetailsDto::fromDomain($roomBooking->details()),
                price: RoomPriceDto::fromDomain($roomBooking->price())
            );
        }

        return $dtos;
    }
}
