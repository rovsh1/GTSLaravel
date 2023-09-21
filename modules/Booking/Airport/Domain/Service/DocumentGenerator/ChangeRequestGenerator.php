<?php

namespace Module\Booking\Airport\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Application\Service\StatusStorage;
use Module\Booking\Common\Domain\Adapter\AdministratorAdapterInterface;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Service\DocumentGenerator\AbstractRequestGenerator;
use Module\Booking\HotelBooking\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\Order\Domain\Repository\GuestRepositoryInterface;
use Module\Shared\Domain\Service\CompanyRequisitesInterface;
use Module\Shared\Enum\ContactTypeEnum;

class ChangeRequestGenerator extends AbstractRequestGenerator
{
    public function __construct(
        private readonly AdministratorAdapterInterface $administratorAdapter,
        private readonly StatusStorage $statusStorage,
        CompanyRequisitesInterface $companyRequisites
    ) {
        parent::__construct($companyRequisites);
    }

    protected function getTemplateName(): string
    {
        return 'airport.change_request';
    }

    protected function getBookingAttributes(BookingInterface $booking): array
    {
        $administrator = $this->administratorAdapter->getManagerByBookingId($booking->id()->value());

        return [
            'reservCreatedAt' => $booking->createdAt()->format('d.m.Y H:i:s'),
            'reservUpdatedAt' => now()->format('d.m.Y H:i:s'),//@todo правильно ли, что тут now()?
//            'hotelName' => $booking->hotelInfo()->name(),
//            'hotelPhone' => $phones,
//            'cityName' => $hotelDto->cityName,
            'reservStartDate' => $booking->period()->dateFrom()->format('d.m.Y'),
            'reservEndDate' => $booking->period()->dateTo()->format('d.m.Y'),
            'reservNightCount' => $booking->period()->nightsCount(),
            'reservNumber' => $booking->id()->value(),
            'reservStatus' => $this->statusStorage->get($booking->status())->name,
            'managerName' => $administrator?->name ?? $administrator?->presentation,//@todo надо ли?
            'managerPhone' => $administrator?->phone,
            'managerEmail' => $administrator?->email,
        ];
    }
}
