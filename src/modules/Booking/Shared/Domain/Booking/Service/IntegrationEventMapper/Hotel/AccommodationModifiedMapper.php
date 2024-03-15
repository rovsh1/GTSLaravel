<?php

namespace Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Hotel;

use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\MapperInterface;
use Sdk\Booking\Event\HotelBooking\AccommodationDetailsEdited;
use Sdk\Booking\IntegrationEvent\HotelBooking\AccommodationModified;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class AccommodationModifiedMapper implements MapperInterface
{
    public function __construct(
        private readonly AccommodationRepositoryInterface $accommodationRepository
    ) {}

    public function map(DomainEventInterface $event): array
    {
        assert($event instanceof AccommodationDetailsEdited);

        $accommodation = $this->accommodationRepository->findOrFail($event->accommodation->id());
        $currentDetails = $accommodation->details();
        $beforeDetails = $event->detailsBefore;

        return [
            new AccommodationModified(
                $event->bookingId()->value(),
                $event->accommodation->id()->value(),
                $event->accommodation->roomInfo()->id(),
                $event->accommodation->roomInfo()->name(),
                $currentDetails->serialize(),
                $beforeDetails->serialize()
            )
        ];
    }
}
