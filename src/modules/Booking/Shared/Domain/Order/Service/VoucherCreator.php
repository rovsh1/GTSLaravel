<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Order\Service;

use Module\Booking\Domain\Order\Service\VoucherGenerator;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Order\Entity\Voucher;
use Module\Booking\Shared\Domain\Order\Repository\VoucherRepositoryInterface;

class VoucherCreator
{
    public function __construct(
        private readonly VoucherRepositoryInterface $repository,
        private readonly VoucherGenerator $voucherGenerator
    ) {}

    public function create(Booking $booking): Voucher
    {
        $voucher = $this->repository->create($booking->id()->value());
        $this->voucherGenerator->generate($voucher, $booking);

        return $voucher;
    }
}
