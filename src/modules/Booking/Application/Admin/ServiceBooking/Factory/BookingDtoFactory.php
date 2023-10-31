<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Factory;

use Module\Booking\Application\Admin\HotelBooking\Dto\Details\CancelConditionsDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\BookingDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\ServiceTypeDto;
use Module\Booking\Application\Admin\Shared\Factory\BookingPriceDtoFactory;
use Module\Booking\Application\Admin\Shared\Factory\StatusDtoFactory;
use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Shared\Contracts\Service\TranslatorInterface;

class BookingDtoFactory
{
    public function __construct(
        private readonly StatusDtoFactory $statusDtoFactory,
        private readonly BookingPriceDtoFactory $bookingPriceDtoFactory,
        private readonly ServiceDetailsDtoFactory $detailsDtoFactory,
        private readonly TranslatorInterface $translator,
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory,
    ) {}

    public function createFromEntity(BookingInterface $booking): BookingDto
    {
        assert($booking instanceof Booking);

        $details = $this->detailsRepositoryFactory->build($booking)->find($booking->id());

        return new BookingDto(
            id: $booking->id()->value(),
            status: $this->statusDtoFactory->get($booking->status()),
            orderId: $booking->orderId()->value(),
            createdAt: $booking->timestamps()->createdDate(),
            creatorId: $booking->creatorId()->value(),
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
        );
    }
}
