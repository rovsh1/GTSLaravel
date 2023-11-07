<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order\Voucher;

use Module\Booking\Deprecated\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Order\Service\VoucherCreator;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SendVoucher implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingUpdater $bookingUpdater,
        private readonly VoucherCreator $voucherCreator,
    ) {}

    public function execute(int $id): void
    {
        $booking = $this->repository->find($id);
        $booking->generateVoucher($this->voucherCreator);
        $this->bookingUpdater->store($booking);
    }
}
