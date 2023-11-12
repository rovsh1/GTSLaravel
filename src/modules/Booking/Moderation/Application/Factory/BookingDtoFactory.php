<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Factory;

use Module\Booking\Moderation\Application\Dto\Details\CancelConditionsDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\BookingDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\ServiceTypeDto;
use Module\Booking\Shared\Application\Factory\BookingStatusDtoFactory;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Shared\Contracts\Service\TranslatorInterface;

class BookingDtoFactory
{
    public function __construct(
        private readonly BookingStatusDtoFactory $statusDtoFactory,
        private readonly BookingPriceDtoFactory $bookingPriceDtoFactory,
        private readonly ServiceDetailsDtoFactory $detailsDtoFactory,
        private readonly TranslatorInterface $translator,
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory,
    ) {}

    public function createFromEntity(Booking $booking): BookingDto
    {
        assert($booking instanceof Booking);

        $details = $this->detailsRepositoryFactory->build($booking)->find($booking->id());

        return new BookingDto(
            id: $booking->id()->value(),
            status: $this->statusDtoFactory->get($booking->status()),
            orderId: $booking->orderId()->value(),
            createdAt: $booking->timestamps()->createdAt(),
            creatorId: $booking->context()->creatorId()->value(),
            prices: $this->bookingPriceDtoFactory->createFromEntity($booking->prices()),
            cancelConditions: $booking->cancelConditions() !== null
                ? CancelConditionsDto::fromDomain($booking->cancelConditions())
                : null,
            note: $booking->note(),
            serviceType: new ServiceTypeDto(
                id: $booking->serviceType()->value,
                name: $this->translator->translateEnum($booking->serviceType()),
            ),
            details: $details !== null
                ? $this->detailsDtoFactory->createFromEntity($details)
                : null,
            source: $booking->context()->source(),
        );
    }
}
