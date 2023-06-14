<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase\Admin\Request;

use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\Common\Domain\Service\RequestCreator;
use Module\Booking\Common\Domain\Service\RequestRules;
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
