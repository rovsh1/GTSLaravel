<?php

namespace Module\Booking\Airport\Domain\Service\DocumentGenerator;

use Module\Booking\Airport\Domain\Entity\Booking;
use Module\Booking\Airport\Domain\Repository\ContractRepositoryInterface;
use Module\Booking\Common\Application\Service\StatusStorage;
use Module\Booking\Common\Domain\Adapter\AdministratorAdapterInterface;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Service\DocumentGenerator\AbstractRequestGenerator;
use Module\Booking\Order\Domain\Repository\GuestRepositoryInterface;
use Module\Shared\Domain\Service\CompanyRequisitesInterface;
use Module\Shared\Enum\Booking\AirportServiceTypeEnum;

class ReservationRequestGenerator extends AbstractRequestGenerator
{
    public function __construct(
        private readonly AdministratorAdapterInterface $administratorAdapter,
        private readonly StatusStorage $statusStorage,
        private readonly GuestRepositoryInterface $guestRepository,
        private readonly ContractRepositoryInterface $contractRepository,
        CompanyRequisitesInterface $companyRequisites
    ) {
        parent::__construct($companyRequisites);
    }

    protected function getTemplateName(): string
    {
        return 'airport.reservation_request';
    }

    protected function getBookingAttributes(BookingInterface|Booking $booking): array
    {
        $administrator = $this->administratorAdapter->getManagerByBookingId($booking->id()->value());

        $contract = $this->contractRepository->find($booking->serviceInfo()->id());
        $contractNumber = $contract?->id()->value();
        $contractDate = $contract !== null ? (string)$contract->dateStart() : null;
        $airportDirector = '{airportDirector}';
        $inn = '{inn}';

        $guests = $this->guestRepository->get($booking->guestIds());

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
            'date' => $booking->date()->format('d.m.Y'),
            'time' => $booking->date()->format('H:i'),
            'flightNumber' => $booking->additionalInfo()->flightNumber(),
            'reservNumber' => $booking->id()->value(),
            'reservCreatedAt' => $booking->createdAt()->format('d.m.Y H:i'),
            'reservStatus' => $this->statusStorage->get($booking->status())->name,
            'city' => '{city}',
            'country' => '{country}',
            'managerName' => $administrator?->name ?? $administrator?->presentation,//@todo надо ли?
            'managerPhone' => $administrator?->phone,
            'managerEmail' => $administrator?->email,
        ];
    }
}
