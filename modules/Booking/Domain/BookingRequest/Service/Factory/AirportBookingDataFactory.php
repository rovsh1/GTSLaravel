<?php

namespace Module\Booking\Domain\BookingRequest\Service\Factory;

use Module\Booking\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Repository\Details\CIPRoomInAirportRepositoryInterface;
use Module\Booking\Domain\BookingRequest\Service\Dto\AirportBooking\AirportDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\AirportBooking\ContractDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\AirportBooking\ServiceDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\GuestDto;
use Module\Booking\Domain\BookingRequest\Service\TemplateData\AirportBooking;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Booking\Domain\Order\Entity\Guest;
use Module\Booking\Domain\Order\Repository\GuestRepositoryInterface;
use Module\Booking\Domain\Shared\Adapter\CountryAdapterInterface;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Shared\Contracts\Service\TranslatorInterface;
use Module\Shared\Enum\GenderEnum;

class AirportBookingDataFactory
{

    /** @var array<int, string> $countryNamesIndexedId */
    private array $countryNamesIndexedId;

    public function __construct(
        private readonly CIPRoomInAirportRepositoryInterface $detailsRepository,
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
            $this->translator->translateEnum($bookingDetails->serviceType())
        );

        return match ($requestType) {
            RequestTypeEnum::BOOKING,
            RequestTypeEnum::CHANGE,
            RequestTypeEnum::CANCEL => new AirportBooking\BookingRequest(
                $airportDto,
                $contractDto,
                $serviceDto,
                $guests,
                $bookingDetails->flightNumber(),
                $bookingDetails->serviceDate()
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
