<?php

declare(strict_types=1);

namespace Module\Booking\Application\HotelBooking\UseCase\Admin\Voucher;


use Module\Booking\Application\Shared\Response\VoucherDto;
use Module\Booking\Domain\Shared\Repository\VoucherRepositoryInterface;
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
