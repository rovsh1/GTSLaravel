<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Application\UseCase;

use Module\Supplier\Payment\Application\Factory\BookingDtoFactory;
use Module\Supplier\Payment\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Supplier\Payment\Domain\Payment\ValueObject\PaymentId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetWaitingPaymentBookings implements UseCaseInterface
{
    public function __construct(
        private readonly BookingDtoFactory $dtoFactory,
        private readonly BookingRepositoryInterface $repository,
    ) {}

    public function execute(int $paymentId): array
    {
        $bookings = $this->repository->getForWaitingPayment(new PaymentId($paymentId));

        return $this->dtoFactory->collection($bookings);
    }
}
