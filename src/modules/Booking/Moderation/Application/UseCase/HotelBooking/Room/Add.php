<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking\Room;

use Module\Booking\Moderation\Application\RequestDto\AddRoomRequestDto;
use Module\Booking\Moderation\Application\Service\AccommodationChecker;
use Module\Booking\Moderation\Application\Service\RoomBookingFactory;
use Module\Booking\Shared\Domain\Booking\Event\HotelBooking\RoomAdded;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Shared\Contracts\Service\SafeExecutorInterface;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

final class Add implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly RoomBookingFactory $roomBookingBuilder,
        private readonly AccommodationChecker $accommodationChecker,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly SafeExecutorInterface $executor,
    ) {
    }

    public function execute(AddRoomRequestDto $requestDto): void
    {
        $booking = $this->bookingRepository->findOrFail(new BookingId($requestDto->bookingId));

        $this->accommodationChecker->validate(
            orderId: $booking->orderId()->value(),
            roomId: $requestDto->roomId,
            rateId: $requestDto->rateId,
            isResident: $requestDto->isResident,
            guestsCount: 1
        );

        $this->roomBookingBuilder->fromRequest($requestDto);

        $this->executor->execute(function () use ($requestDto, $booking) {
            $roomBooking = $this->roomBookingBuilder->create($booking->id());

            $this->eventDispatcher->dispatch(new RoomAdded($booking, $roomBooking));
        });
    }
}
