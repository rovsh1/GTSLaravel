<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Service;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Entity\RoomBooking;
use Module\Booking\HotelBooking\Domain\Event\RoomAdded;
use Module\Booking\HotelBooking\Domain\Event\RoomDeleted;
use Module\Booking\HotelBooking\Domain\Event\RoomEdited;
use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\GuestCollection;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingId;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\HotelBooking\Domain\ValueObject\RoomPrice;
use Module\Booking\HotelBooking\Infrastructure\Repository\BookingRepository;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class RoomBookingService
{
    public function __construct(
        private readonly BookingRepository $bookingRepository,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
        private readonly RoomAvailabilityValidator $roomAvailabilityValidator,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    /**
     * @param BookingId $bookingId
     * @param RoomBookingStatusEnum $status
     * @param RoomInfo $roomInfo
     * @param GuestCollection $guests
     * @param RoomBookingDetails $details
     * @param RoomPrice $price
     * @return void
     * @throws \Throwable
     */
    public function addRoomBooking(
        BookingId $bookingId,
        RoomBookingStatusEnum $status,
        RoomInfo $roomInfo,
        GuestCollection $guests,
        RoomBookingDetails $details,
        RoomPrice $price
    ): void {
        $booking = $this->findBookingOrFail($bookingId);
        $this->roomAvailabilityValidator->ensureRoomAvailable($booking, $roomInfo->id(), $details->isResident());
        $roomBooking = $this->roomBookingRepository->create(
            $bookingId->value(),
            $status,
            $roomInfo,
            $guests,
            $details,
            $price
        );
        $this->eventDispatcher->dispatch(
            new RoomAdded(
                $booking,
                $roomBooking,
            )
        );
    }

    public function updateRoomBooking(
        RoomBookingId $id,
        BookingId $bookingId,
        RoomBookingStatusEnum $status,
        RoomInfo $roomInfo,
        GuestCollection $guests,
        RoomBookingDetails $details,
        RoomPrice $price
    ): void {
        $booking = $this->findBookingOrFail($bookingId);
        $roomBooking = $booking->roomBookings()->first(fn(RoomBooking $roomBooking) => $roomBooking->id()->isEqual($id));
        if ($roomBooking === null) {
            throw new EntityNotFoundException('Room booking not found');
        }
        $isHotelRoomChanged = $roomBooking->roomInfo()->id() !== $roomInfo->id();
        $isResidentChanged = $roomBooking->details()->isResident() !== $details->isResident();
        if ($isHotelRoomChanged || $isResidentChanged) {
            $this->roomAvailabilityValidator->ensureRoomAvailable($booking, $roomInfo->id(), $details->isResident());
        }
        $this->roomBookingRepository->delete($id->value());
        $roomBooking = $this->roomBookingRepository->create(
            $bookingId->value(),
            $status,
            $roomInfo,
            $guests,
            $details,
            $price
        );

        $this->eventDispatcher->dispatch(
            new RoomEdited(
                $booking,
                $roomBooking
            )
        );
    }

    public function deleteRoomBooking(BookingId $bookingId, RoomBookingId $id): void
    {
        $booking = $this->findBookingOrFail($bookingId);
        $this->roomBookingRepository->delete($id->value());
        $this->eventDispatcher->dispatch(
            new RoomDeleted(
                $booking,
                $id
            )
        );
    }

    private function findBookingOrFail(BookingId $bookingId): Booking
    {
        $booking = $this->bookingRepository->find($bookingId->value());
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $booking;
    }
}
