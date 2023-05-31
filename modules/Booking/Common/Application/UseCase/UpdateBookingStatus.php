<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\UseCase;

use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateBookingStatus implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository
    ) {}

    public function execute(int $bookingId, int $statusId): void
    {
        $statusEnum = BookingStatusEnum::from($statusId);
        $booking = $this->repository->find($bookingId);
        $booking->setStatus($statusEnum);
        $this->repository->update($booking);
    }
}
