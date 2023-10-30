<?php

declare(strict_types=1);

namespace Module\Booking\Application\AirportBooking\UseCase\Admin;

use Module\Booking\Deprecated\AirportBooking\Repository\BookingGuestRepositoryInterface;
use Module\Booking\Deprecated\AirportBooking\Repository\BookingRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CopyBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingGuestRepositoryInterface $bookingGuestRepository,
    ) {}

    public function execute(int $id): int
    {
        $booking = $this->repository->find($id);
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }
        $newBooking = $this->repository->create(
            orderId: $booking->orderId(),
            creatorId: $booking->creatorId(),
            serviceId: $booking->serviceInfo()->id(),
            airportId: $booking->airportInfo()->id(),
            date: $booking->date(),
            price: $booking->price(),
            additionalInfo: $booking->additionalInfo(),
            cancelConditions: $booking->cancelConditions(),
            note: $booking->note(),
        );

        foreach ($booking->guestIds() as $guestId) {
            $this->bookingGuestRepository->bind($booking->id(), $guestId);
        }

        return $newBooking->id()->value();
    }
}
