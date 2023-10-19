<?php

namespace Module\Booking\Domain\BookingRequest\Service\TemplateData;

use Module\Booking\Application\Admin\Shared\Service\StatusStorage;
use Module\Booking\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Entity\HotelBooking as DetailsEntity;
use Module\Booking\Domain\Booking\Entity\HotelRoomBooking;
use Module\Booking\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingCollection;
use Module\Booking\Domain\BookingRequest\Service\Dto\HotelBooking\BookingDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\HotelBooking\BookingPeriodDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\HotelBooking\GuestDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\HotelBooking\HotelInfoDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\HotelBooking\RoomDto;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Booking\Domain\Order\Entity\Guest;
use Module\Booking\Domain\Order\Repository\GuestRepositoryInterface;
use Module\Booking\Domain\Shared\Adapter\CountryAdapterInterface;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Catalog\Application\Admin\ResponseDto\ContactDto;
use Module\Shared\Enum\ContactTypeEnum;
use Module\Shared\Enum\GenderEnum;

class HotelBookingDataFactory
{

    private array $countryNamesIndexedId;

    public function __construct(
        private readonly HotelBookingRepositoryInterface $hotelBookingRepository,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly GuestRepositoryInterface $guestRepository,
        private readonly StatusStorage $statusStorage,
        CountryAdapterInterface $countryAdapter,
    ) {
        $countries = $countryAdapter->get();
        $this->countryNamesIndexedId = collect($countries)->keyBy('id')->map->name->all();
    }

    public function build(Booking $booking, RequestTypeEnum $requestType): TemplateDataInterface
    {
        $bookingDetails = $this->hotelBookingRepository->findOrFail($booking->id());
        $rooms = $this->roomBookingRepository->get($bookingDetails->roomBookings());
        $bookingDto = $this->buildBookingDto($booking, $bookingDetails);
        $roomsDto = $this->buildRoomsDto($rooms, $bookingDetails);

        return match ($requestType) {
            RequestTypeEnum::BOOKING => new HotelBooking\BookingRequest($bookingDto, $roomsDto),
            RequestTypeEnum::CHANGE =>  new HotelBooking\BookingRequest($bookingDto, $roomsDto),
            RequestTypeEnum::CANCEL =>  new HotelBooking\BookingRequest($bookingDto, $roomsDto),
        };
    }

    private function buildBookingDto(Booking $booking, DetailsEntity $details): BookingDto
    {
        $hotelDto = $this->hotelAdapter->findById($details->hotelInfo()->id());

        return new BookingDto(
            number: $booking->id()->value(),
            status: $this->statusStorage->get($booking->status())->name,
            hotel: new HotelInfoDto(
                $details->hotelInfo()->id(),
                $details->hotelInfo()->name(),
                $this->buildHotelPhones($hotelDto->contacts),
                $hotelDto->cityName,
            ),
            period: new BookingPeriodDto(
                $details->bookingPeriod()->dateFrom()->format('d.m.Y H:i:s'),
                $details->bookingPeriod()->dateTo()->format('d.m.Y H:i:s'),
                $details->bookingPeriod()->nightsCount()
            ),
            createdAt: $booking->timestamps()->createdDate()->format('d.m.Y H:i:s'),
            updatedAt: $booking->timestamps()->updatedDate()->format('d.m.Y H:i:s'),
        );
    }

    private function buildHotelPhones(array $contacts): string
    {
        return collect($contacts)
            ->map(function (ContactDto $contactDto) {
                if ($contactDto->type === ContactTypeEnum::PHONE->value) {
                    return $contactDto->value;
                }

                return null;
            })
            ->filter()
            ->implode(', ');
    }

    /**
     * @param RoomBookingCollection $roomBookings
     * @param DetailsEntity $bookingDetails
     * @return RoomDto[]
     */
    private function buildRoomsDto(RoomBookingCollection $roomBookings, DetailsEntity $bookingDetails): array
    {
        if ($roomBookings->count() === 0) {
            return [];
        }
        $hotelPriceRates = $this->hotelAdapter->getHotelRates($bookingDetails->hotelInfo()->id());
        $hotelPriceRatesIndexedId = collect($hotelPriceRates)->keyBy('id');

        return $roomBookings->map(function (HotelRoomBooking $roomBooking) use ($bookingDetails, $hotelPriceRatesIndexedId) {
                $checkInTime = $bookingDetails->hotelInfo()->checkInTime()->value();
                if ($roomBooking->details()->earlyCheckIn() !== null) {
                    $checkInTime = $roomBooking->details()->earlyCheckIn()->timePeriod()->from();
                }
                $checkOutTime = $bookingDetails->hotelInfo()->checkOutTime()->value();
                if ($roomBooking->details()->lateCheckOut() !== null) {
                    $checkOutTime = $roomBooking->details()->lateCheckOut()->timePeriod()->to();
                }

                return new RoomDto(
                    $roomBooking->roomInfo()->name(),
                    $hotelPriceRatesIndexedId[$roomBooking->details()->rateId()]->name,
                    $checkInTime,
                    $checkOutTime,
                    $this->buildGuests($roomBooking->guestIds()),
                    $roomBooking->details()->guestNote(),
                );
            }
        );
    }

    /**
     * @param GuestIdCollection $guestIds
     * @return GuestDto[]
     */
    private function buildGuests(GuestIdCollection $guestIds): array
    {
        if ($guestIds->count() === 0) {
            return [];
        }
        $guests = $this->guestRepository->get($guestIds);

        return collect($guests)->map(fn(Guest $guest) => new GuestDto(
            $guest->fullName(),
            $guest->gender() === GenderEnum::MALE ? 'Мужской' : 'Женский',
            $this->countryNamesIndexedId[$guest->countryId()]
        ))->all();
    }
}
