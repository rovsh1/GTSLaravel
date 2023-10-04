<?php

declare(strict_types=1);

namespace Module\Booking\Application\Shared\Support\UseCase\Admin\Request;

use Module\Booking\Domain\Shared\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Module\Booking\Domain\Shared\Service\RequestCreator;
use Module\Booking\Domain\Shared\Service\RequestRules;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SendRequest implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly RequestCreator $requestCreator,
        private readonly BookingUpdater $bookingUpdater
    ) {}

    public function execute(int $id): void
    {
        $booking = $this->repository->find($id);
        $booking->generateRequest(new RequestRules(), $this->requestCreator);
        $this->bookingUpdater->store($booking);
    }
}
