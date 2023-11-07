<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order\Voucher;


use Module\Booking\Domain\Order\Repository\VoucherRepositoryInterface;
use Module\Booking\Moderation\Application\Dto\VoucherDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetVouchers implements UseCaseInterface
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
