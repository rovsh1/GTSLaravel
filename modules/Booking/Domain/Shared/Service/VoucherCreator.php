<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Service;

use Module\Booking\Domain\HotelBooking\Service\DocumentGenerator\VoucherGenerator;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Entity\Voucher;
use Module\Booking\Domain\Shared\Repository\VoucherRepositoryInterface;

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
