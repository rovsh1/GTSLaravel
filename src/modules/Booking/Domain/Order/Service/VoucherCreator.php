<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Order\Service;

use Module\Booking\Deprecated\HotelBooking\Service\DocumentGenerator\VoucherGenerator;
use Module\Booking\Domain\Order\Entity\Voucher;
use Module\Booking\Domain\Order\Repository\VoucherRepositoryInterface;
use Module\Booking\Domain\Shared\Entity\BookingInterface;

class VoucherCreator
{
    public function __construct(
        private readonly VoucherRepositoryInterface $repository,
        private readonly VoucherGenerator $voucherGenerator
    ) {}

    public function create(BookingInterface $booking): Voucher
    {
        $voucher = $this->repository->create($booking->id()->value());
        $this->voucherGenerator->generate($voucher, $booking);

        return $voucher;
    }
}
