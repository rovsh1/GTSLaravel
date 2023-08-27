<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\UseCase\Admin\Tourist;

use Module\Booking\Order\Domain\Event\TouristDeleted;
use Module\Booking\Order\Domain\Repository\TouristRepositoryInterface;
use Module\Booking\Order\Domain\ValueObject\TouristId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Delete implements UseCaseInterface
{
    public function __construct(
        private readonly TouristRepositoryInterface $touristRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(int $id): void
    {
        $touristId = new TouristId($id);
        $this->touristRepository->delete($touristId);

        $this->eventDispatcher->dispatch(new TouristDeleted($touristId));
    }
}
