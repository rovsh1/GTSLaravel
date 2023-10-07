<?php

declare(strict_types=1);

namespace Module\Booking\Application\ServiceBooking\UseCase\Admin;

use Module\Booking\Domain\ServiceBooking\Repository\BookingRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CopyBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
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
            cityId: $booking->serviceInfo()->cityId(),
            price: $booking->price(),
            cancelConditions: $booking->cancelConditions(),
            note: $booking->note(),
        );

        return $newBooking->id()->value();
    }
}
