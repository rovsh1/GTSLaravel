<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin\Voucher;


use Module\Booking\Common\Application\Response\VoucherDto;
use Module\Booking\Common\Domain\Repository\VoucherRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetBookingVouchers implements UseCaseInterface
{
    public function __construct(
        private readonly VoucherRepositoryInterface $repository
    ) {}

    public function execute(int $bookingId): array
    {
        $vouchers = $this->repository->findByBookingId($bookingId);

        return VoucherDto::collectionFromDomain($vouchers);
    }
}
