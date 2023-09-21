<?php

namespace Module\Booking\Airport\Domain\Service\DocumentGenerator;

use Module\Booking\Airport\Domain\Entity\Booking;
use Module\Booking\Common\Application\Service\StatusStorage;
use Module\Booking\Common\Domain\Adapter\AdministratorAdapterInterface;
use Module\Booking\Common\Domain\Adapter\ClientAdapterInterface;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Service\DocumentGenerator\AbstractRequestGenerator;
use Module\Shared\Domain\Service\CompanyRequisitesInterface;
use Module\Shared\Enum\Booking\AirportServiceTypeEnum;

class ReservationRequestGenerator extends AbstractRequestGenerator
{
    public function __construct(
        private readonly AdministratorAdapterInterface $administratorAdapter,
        private readonly StatusStorage $statusStorage,
        private readonly ClientAdapterInterface $clientAdapter,
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
        //@todo получение клиента
//        $client = $this->clientAdapter->find();

        return [
            'serviceName' => $booking->serviceInfo()->name(),
            'serviceTypeName' => $booking->serviceInfo()->type() === AirportServiceTypeEnum::MEETING_IN_AIRPORT
                ? 'ВСТРЕЧУ'
                : 'ПРОВОДЫ',
            'airportName' => $booking->airportInfo()->name(),
//            'reservStartDate' => $booking->period()->dateFrom()->format('d.m.Y'),
//            'reservEndDate' => $booking->period()->dateTo()->format('d.m.Y'),
//            'reservNightCount' => $booking->period()->nightsCount(),
            'reservNumber' => $booking->id()->value(),
            'reservCreatedAt' => $booking->createdAt()->format('d.m.Y'),
            'reservStatus' => $this->statusStorage->get($booking->status())->name,
            'city' => '{city}',
            'country' => '{country}',
            'managerName' => $administrator?->name ?? $administrator?->presentation,//@todo надо ли?
            'managerPhone' => $administrator?->phone,
            'managerEmail' => $administrator?->email,
        ];
    }
}
