<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Entity\Voucher;
use Module\Booking\Common\Domain\Repository\VoucherRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\DocumentGenerator\VoucherGenerator;

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
