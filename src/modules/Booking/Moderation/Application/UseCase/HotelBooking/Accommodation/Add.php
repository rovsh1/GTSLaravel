<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking\Accommodation;

use Module\Booking\Moderation\Application\RequestDto\AddAccommodationRequestDto;
use Module\Booking\Moderation\Application\Service\AccommodationChecker;
use Module\Booking\Moderation\Application\Service\AccommodationFactory;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\Event\HotelBooking\AccommodationAdded;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

final class Add implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly AccommodationFactory $accommodationFactory,
        private readonly AccommodationChecker $accommodationChecker,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function execute(AddAccommodationRequestDto $requestDto): void
    {
        $booking = $this->bookingUnitOfWork->findOrFail(new BookingId($requestDto->bookingId));

        $this->accommodationChecker->validate(
            orderId: $booking->orderId()->value(),
            roomId: $requestDto->roomId,
            rateId: $requestDto->rateId,
            isResident: $requestDto->isResident,
            guestsCount: 1
        );

        $this->accommodationFactory->fromRequest($requestDto);

        $this->bookingUnitOfWork->touch($booking->id());
        $this->bookingUnitOfWork->commiting(function () use ($requestDto, $booking) {
            $accommodation = $this->accommodationFactory->create($booking->id());

            $this->eventDispatcher->dispatch(new AccommodationAdded($booking, $accommodation));
        });
        $this->bookingUnitOfWork->commit();
    }
}
