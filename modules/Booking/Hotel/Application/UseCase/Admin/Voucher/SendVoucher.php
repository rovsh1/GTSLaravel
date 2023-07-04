<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin\Voucher;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SendVoucher implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingUpdater $bookingUpdater
    ) {}

    public function execute(int $id): void {
        $booking = $this->repository->find($id);
    }
}
