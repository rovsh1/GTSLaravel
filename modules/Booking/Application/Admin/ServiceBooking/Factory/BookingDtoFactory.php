<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Factory;

use Module\Booking\Application\Admin\HotelBooking\Dto\Details\CancelConditionsDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\BookingDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\ServiceTypeDto;
use Module\Booking\Application\Admin\Shared\Factory\AbstractBookingDtoFactory;
use Module\Booking\Application\Admin\Shared\Factory\BookingPriceDtoFactory;
use Module\Booking\Application\Admin\Shared\Service\StatusStorage;
use Module\Booking\Domain\ServiceBooking\Repository\DetailsRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\ServiceBooking;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Shared\Contracts\Service\TranslatorInterface;

class BookingDtoFactory extends AbstractBookingDtoFactory
{
    public function __construct(
        StatusStorage $statusStorage,
        private readonly BookingPriceDtoFactory $bookingPriceDtoFactory,
        private readonly ServiceDetailsDtoFactory $detailsDtoFactory,
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly TranslatorInterface $translator
    ) {
        parent::__construct($statusStorage);
    }

    public function createFromEntity(BookingInterface $booking): BookingDto
    {
        assert($booking instanceof ServiceBooking);

        $details = $this->detailsRepository->find($booking->id());

        return new BookingDto(
            id: $booking->id()->value(),
            status: $this->statusStorage->get($booking->status()),
            orderId: $booking->orderId()->value(),
            createdAt: $booking->createdAt(),
            creatorId: $booking->creatorId()->value(),
            price: $this->bookingPriceDtoFactory->createFromEntity($booking->price()),
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
