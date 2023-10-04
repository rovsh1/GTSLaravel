<?php

declare(strict_types=1);

namespace Module\Booking\Application\HotelBooking\UseCase\Admin\Voucher;

use Module\Booking\Domain\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Module\Booking\Domain\Shared\Service\VoucherCreator;
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
