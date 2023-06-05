<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase\Request;

use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Domain\Service\RequestCreator;
use Module\Booking\Common\Domain\Service\RequestRules;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SendRequest implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly RequestCreator $requestCreator
    ) {}

    public function execute(int $id): void
    {
        $booking = $this->repository->find($id);
        $booking->generateRequest(new RequestRules(), $this->requestCreator);
        $events = $booking->pullEvents();
        $this->eventDispatcher->dispatchAll(...$events);
        $this->repository->update($booking);
    }
}
