<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin\Voucher;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\Common\Domain\Service\VoucherCreator;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
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
