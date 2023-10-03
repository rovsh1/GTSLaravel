<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Application\UseCase\Admin;

use Module\Booking\Transfer\Domain\Booking\Repository\BookingRepositoryInterface;
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
            price: $booking->price(),
            note: $booking->note(),
            cityId: $booking->serviceInfo()->cityId(),
            serviceId: $booking->serviceInfo()->id(),
            cancelConditions: $booking->cancelConditions(),
        );
        
        return $newBooking->id()->value();
    }
}
