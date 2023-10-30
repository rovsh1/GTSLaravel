<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Order\UseCase\Guest;

use Module\Booking\Domain\Order\Event\GuestDeleted;
use Module\Booking\Domain\Order\Repository\GuestRepositoryInterface;
use Module\Booking\Domain\Shared\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Delete implements UseCaseInterface
{
    public function __construct(
        private readonly GuestRepositoryInterface $guestRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(int $id): void
    {
        $guestId = new GuestId($id);
        $this->guestRepository->delete($guestId);

        $this->eventDispatcher->dispatch(new GuestDeleted($guestId));
    }
}
