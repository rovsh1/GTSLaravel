<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\UseCase\Admin\Guest;

use Module\Booking\Order\Domain\Event\GuestDeleted;
use Module\Booking\Order\Domain\Repository\GuestRepositoryInterface;
use Module\Booking\Order\Domain\ValueObject\GuestId;
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
