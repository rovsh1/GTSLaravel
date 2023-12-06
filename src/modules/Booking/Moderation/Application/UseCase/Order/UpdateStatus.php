<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order;

use Module\Booking\Moderation\Application\Exception\OrderHasBookingInProgressException;
use Module\Booking\Moderation\Application\Exception\OrderWithoutBookingsException;
use Module\Booking\Moderation\Domain\Order\Exception\OrderHasBookingInProgress;
use Module\Booking\Moderation\Domain\Order\Exception\OrderWithoutBookings;
use Module\Booking\Moderation\Domain\Order\Service\StatusUpdater;
use Module\Booking\Shared\Domain\Order\DbContext\OrderDbContextInterface;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Enum\Order\OrderStatusEnum;

class UpdateStatus implements UseCaseInterface
{
    public function __construct(
        private readonly OrderDbContextInterface $orderDbContext,
        private readonly StatusUpdater $statusUpdater,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $orderId, int $statusId): void
    {
        $statusEnum = OrderStatusEnum::from($statusId);
        $order = $this->orderDbContext->findOrFail(new OrderId($orderId));

        try {
            $this->statusUpdater->update($order, $statusEnum);
        } catch (OrderHasBookingInProgress $e) {
            throw new OrderHasBookingInProgressException($e);
        } catch (OrderWithoutBookings $e) {
            throw new OrderWithoutBookingsException($e);
        }

        $this->orderDbContext->store($order);
        $this->eventDispatcher->dispatch(...$order->pullEvents());
    }
}
