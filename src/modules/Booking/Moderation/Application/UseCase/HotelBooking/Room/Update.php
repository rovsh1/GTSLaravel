<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking\Room;

use Module\Booking\Moderation\Application\RequestDto\UpdateRoomRequestDto;
use Module\Booking\Moderation\Application\Service\AccommodationChecker;
use Module\Booking\Moderation\Application\Service\RoomBookingFactory;
use Module\Booking\Shared\Domain\Booking\Entity\HotelRoomBooking;
use Module\Booking\Shared\Domain\Booking\Event\HotelBooking\RoomEdited;
use Module\Booking\Shared\Domain\Booking\Event\HotelBooking\RoomReplaced;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\HotelBooking\BookingGuestRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Shared\Contracts\Service\SafeExecutorInterface;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

final class Update implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly RoomBookingFactory $roomBookingFactory,
        private readonly AccommodationChecker $accommodationChecker,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
        private readonly BookingGuestRepositoryInterface $bookingGuestRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly SafeExecutorInterface $executor,
    ) {
    }

    public function execute(UpdateRoomRequestDto $requestDto): void
    {
        $booking = $this->bookingRepository->findOrFail(new BookingId($requestDto->bookingId));
        $currentRoomBooking = $this->roomBookingRepository->findOrFail(new RoomBookingId($requestDto->roomBookingId));

        $this->accommodationChecker->validate(
            orderId: $booking->orderId()->value(),
            roomId: $requestDto->roomId,
            rateId: $requestDto->rateId,
            isResident: $requestDto->isResident,
            guestsCount: $currentRoomBooking->guestIds()->count()
        );

        $this->roomBookingFactory->fromRequest($requestDto);

        if ($currentRoomBooking->roomInfo()->id() === $requestDto->roomId) {
            $this->doUpdate($booking, $currentRoomBooking);
        } else {
            $this->doReplace($booking, $currentRoomBooking);
        }
    }

    private function doUpdate($booking, HotelRoomBooking $currentRoomBooking): void
    {
        $roomDetails = $this->roomBookingFactory->buildDetails();
        if ($currentRoomBooking->details()->isEqual($roomDetails)) {
            return;
        }

        $currentRoomBooking->updateDetails($roomDetails);

        $this->eventDispatcher->dispatch(new RoomEdited($booking, $currentRoomBooking));
    }

    private function doReplace($booking, HotelRoomBooking $beforeBoomBooking): void
    {
        $this->executor->execute(function () use ($booking, $beforeBoomBooking) {
            $this->roomBookingRepository->delete($beforeBoomBooking->id());
            $newRoomBooking = $this->roomBookingFactory->create($booking->id());
            $maxGuestCount = $newRoomBooking->roomInfo()->guestsCount();
            foreach ($beforeBoomBooking->guestIds() as $index => $guestId) {
                if ($index + 1 > $maxGuestCount) {
                    break;
                }
                $this->bookingGuestRepository->bind($newRoomBooking->id(), $guestId);
            }

            $this->eventDispatcher->dispatch(new RoomReplaced($booking, $newRoomBooking, $beforeBoomBooking));
        });
    }
}
