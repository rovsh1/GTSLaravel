<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase;

use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Domain\Service\StatusRules\RequestRules;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SendRequest implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository
    ) {}

    public function execute(int $id): void
    {
        $booking = $this->repository->find($id);
        $booking->generateRequest(new RequestRules());
        $this->repository->update($booking);
    }
}
