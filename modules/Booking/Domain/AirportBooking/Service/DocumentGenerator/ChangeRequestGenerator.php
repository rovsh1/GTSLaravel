<?php

namespace Module\Booking\Domain\AirportBooking\Service\DocumentGenerator;

use Module\Booking\Application\Admin\Shared\Service\StatusStorage;
use Module\Booking\Domain\AirportBooking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\AirportBooking\AirportBooking;
use Module\Booking\Domain\Order\Repository\GuestRepositoryInterface;
use Module\Booking\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Module\Booking\Domain\Shared\Adapter\CountryAdapterInterface;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Service\DocumentGenerator\AbstractRequestGenerator;
use Module\Shared\Contracts\Service\CompanyRequisitesInterface;
use Module\Shared\Enum\Booking\AirportServiceTypeEnum;

class ChangeRequestGenerator extends AbstractRequestGenerator
{
    public function __construct(
        private readonly AdministratorAdapterInterface $administratorAdapter,
        private readonly StatusStorage $statusStorage,
        private readonly GuestRepositoryInterface $guestRepository,
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly CountryAdapterInterface $countryAdapter,
        CompanyRequisitesInterface $companyRequisites
    ) {
        parent::__construct($companyRequisites);
    }

    protected function getTemplateName(): string
    {
        return 'airport.change_request';
    }

    protected function getBookingAttributes(BookingInterface|AirportBooking $booking): array
    {
        $administrator = $this->administratorAdapter->getManagerByBookingId($booking->id()->value());

        $contract = $this->supplierAdapter->findAirportServiceContract($booking->serviceInfo()->id());
        $contractNumber = $contract?->id;
        $contractDate = $contract?->dateStart;

        $supplier = $this->supplierAdapter->find($booking->serviceInfo()->supplierId());
        $airportDirector = $supplier->directorFullName;
        $inn = $supplier->inn;
        $reservationChanges = '{reservationChanges}';

        $guests = $this->guestRepository->get($booking->guestIds());
        $countries = $this->countryAdapter->get();
        $countries = collect($countries)->keyBy('id')->map->name;

        return [
            'serviceName' => $booking->serviceInfo()->name(),
            'serviceTypeName' => $booking->serviceInfo()->type() === AirportServiceTypeEnum::MEETING_IN_AIRPORT
                ? 'ВСТРЕЧУ'
                : 'ПРОВОДЫ',
            'airportName' => $booking->airportInfo()->name(),
            'airportDirector' => $airportDirector,
            'contractNumber' => $contractNumber,
            'contractDate' => $contractDate,
            'inn' => $inn,
            'guests' => $guests,
            'guestsCount' => count($guests),
            'countryNamesById' => $countries,
            'date' => $booking->date()->format('d.m.Y'),
            'time' => $booking->date()->format('H:i'),
            'flightNumber' => $booking->additionalInfo()->flightNumber(),
            'reservNumber' => $booking->id()->value(),
            'reservCreatedAt' => $booking->createdAt()->format('d.m.Y H:i'),
            'reservUpdatedAt' => now()->format('d.m.Y H:i'),
            'reservStatus' => $this->statusStorage->get($booking->status())->name,
            'reservationChanges' => $reservationChanges,
            'managerName' => $administrator?->name ?? $administrator?->presentation,//@todo надо ли?
            'managerPhone' => $administrator?->phone,
            'managerEmail' => $administrator?->email,
        ];
    }
}
