<?php

namespace Module\Booking\Requesting\Domain\BookingRequest\Service\Factory;

use Module\Booking\Requesting\Domain\BookingRequest\Service\Dto\AirportBooking\AirportDto;
use Module\Booking\Requesting\Domain\BookingRequest\Service\Dto\AirportBooking\ContractDto;
use Module\Booking\Requesting\Domain\BookingRequest\Service\Dto\GuestDto;
use Module\Booking\Requesting\Domain\BookingRequest\Service\Dto\ServiceDto;
use Module\Booking\Requesting\Domain\BookingRequest\Service\TemplateData\AirportBooking\BookingRequest;
use Module\Booking\Requesting\Domain\BookingRequest\Service\TemplateDataInterface;
use Module\Booking\Requesting\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\CIPMeetingInAirport;
use Module\Booking\Shared\Domain\Booking\Entity\CIPSendoffInAirport;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Guest\Guest;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Shared\Contracts\Adapter\CountryAdapterInterface;
use Module\Shared\Contracts\Service\TranslatorInterface;
use Module\Shared\Enum\GenderEnum;

class AirportBookingDataFactory
{

    /** @var array<int, string> $countryNamesIndexedId */
    private array $countryNamesIndexedId;

    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly GuestRepositoryInterface $guestRepository,
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly TranslatorInterface $translator,
        CountryAdapterInterface $countryAdapter,
    ) {
        $countries = $countryAdapter->get();
        $this->countryNamesIndexedId = collect($countries)->keyBy('id')->map->name->all();
    }

    public function build(Booking $booking, RequestTypeEnum $requestType): TemplateDataInterface
    {
        /** @var CIPMeetingInAirport|CIPSendoffInAirport $bookingDetails */
        $bookingDetails = $this->detailsRepository->findOrFail($booking->id());
        $guests = $this->buildGuests($bookingDetails->guestIds());

        $supplier = $this->supplierAdapter->find($bookingDetails->serviceInfo()->supplierId());
        $contract = $this->supplierAdapter->findAirportServiceContract($bookingDetails->serviceInfo()->id());
        $contractDto = new ContractDto(
            $contract->id,
            $contract?->dateStart,
            $supplier->inn,
        );

        $airport = $this->supplierAdapter->findAirport($bookingDetails->airportId()->value());
        $airportDto = new AirportDto(
            $airport->name,
            $supplier->directorFullName,
        );

        $serviceDto = new ServiceDto(
            $bookingDetails->serviceInfo()->title(),
            $this->translator->translateEnum($bookingDetails->serviceType()),
            $bookingDetails->serviceType()
        );

        $date = $bookingDetails instanceof CIPMeetingInAirport ? $bookingDetails->arrivalDate() : $bookingDetails->departureDate();

        return match ($requestType) {
            RequestTypeEnum::BOOKING,
            RequestTypeEnum::CHANGE,
            RequestTypeEnum::CANCEL => new BookingRequest(
                $airportDto,
                $contractDto,
                $serviceDto,
                $guests,
                $bookingDetails->flightNumber(),
                $date
            ),
        };
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
