<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Application\UseCase;

use Module\Supplier\Payment\Application\Factory\BookingDtoFactory;
use Module\Supplier\Payment\Domain\Payment\ValueObject\PaymentId;
use Module\Supplier\Payment\Infrastructure\Repository\BookingRepository;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetPaymentBookings implements UseCaseInterface
{
    public function __construct(
        private readonly BookingDtoFactory $dtoFactory,
        private readonly BookingRepository $repository,
    ) {}

    public function execute(int $paymentId): array
    {
        $bookings = $this->repository->getPaymentBookings(new PaymentId($paymentId));

        return $this->dtoFactory->collection($bookings);
    }
}
