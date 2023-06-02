<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase;

use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Domain\Service\StatusRules\AdministratorRules;
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
        $booking->updateStatus($statusEnum, new AdministratorRules());
        $this->repository->update($booking);
    }
}
