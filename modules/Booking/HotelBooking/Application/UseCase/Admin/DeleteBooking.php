<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin;

use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\QuotaProcessingMethodFactory;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class DeleteBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly QuotaProcessingMethodFactory $quotaProcessingMethodFactory
    ) {}

    public function execute(int $id): void
    {
        $booking = $this->repository->findOrFail($id);
        $quotaProcessingMethod = $this->quotaProcessingMethodFactory->build($booking->quotaProcessingMethod());
        $quotaProcessingMethod->process($booking);
        $this->repository->delete($id);
    }
}
