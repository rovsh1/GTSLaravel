<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin;

use Module\Booking\Airport\Domain\Repository\BookingGuestRepositoryInterface;
use Module\Booking\Airport\Domain\Repository\BookingRepositoryInterface;
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
            price: $booking->price(),
            date: $booking->date(),
            note: $booking->note(),
            serviceId: $booking->serviceInfo()->id(),
            airportId: $booking->airportInfo()->id(),
            additionalInfo: $booking->additionalInfo(),
            cancelConditions: $booking->cancelConditions(),
        );

        foreach ($booking->guestIds() as $guestId) {
            $this->bookingGuestRepository->bind($booking->id(), $guestId);
        }

        return $newBooking->id()->value();
    }
}
