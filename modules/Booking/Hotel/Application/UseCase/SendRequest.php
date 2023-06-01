<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase;

use Module\Booking\Common\Domain\Service\StatusRules\RequestRules;
use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SendRequest implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository
    ) {}

    public function execute(int $id): void
    {
        $booking = $this->repository->find($id);
        $booking->generateRequest(app(RequestRules::class));
        $this->repository->update($booking);
    }
}
