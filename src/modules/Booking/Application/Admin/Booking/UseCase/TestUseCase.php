<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Booking\UseCase;

use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class TestEvent implements DomainEventInterface
{
    public function __construct(
        public readonly int $bookingId
    ) {
    }
}

class TestUseCase implements UseCaseInterface
{
    public function __construct(
        private readonly DomainEventDispatcherInterface $domainEventDispatcher,
    ) {
    }

    public function execute(): void
    {
        $this->domainEventDispatcher->dispatch(new TestEvent(4));
    }
}
