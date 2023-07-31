<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin;

use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CopyBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository
    ) {}

    public function execute(int $id): int
    {
        $booking = $this->repository->find($id);
        $newBooking = $this->repository->create(
            $booking->orderId(),
            $booking->creatorId(),
            $booking->period(),
            $booking->note(),
            $booking->hotelInfo(),
            $booking->cancelConditions()
        );

        $rooms = $this->roomBookingRepository->get($id);
        foreach ($rooms as $room) {
            $this->roomBookingRepository->create(
                $newBooking->id()->value(),
                $room->status(),
                $room->roomInfo(),
                $room->guests(),
                $room->details(),
                $room->price()
            );
        }

        return $newBooking->id()->value();
    }
}
