<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order\Voucher;

use Module\Booking\Deprecated\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Order\Service\VoucherCreator;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SendVoucher implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly VoucherCreator $voucherCreator,
    ) {}

    public function execute(int $id): void
    {
        $booking = $this->repository->find($id);
        $booking->generateVoucher($this->voucherCreator);
        $this->bookingUpdater->store($booking);
    }
}
