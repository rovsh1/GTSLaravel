<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order;

use Module\Booking\Moderation\Application\Exception\OrderHasBookingInProgressException;
use Module\Booking\Moderation\Application\Exception\OrderWithoutBookingsException;
use Module\Booking\Moderation\Application\ResponseDto\OrderUpdateStatusResponseDto;
use Module\Booking\Moderation\Domain\Order\Exception\OrderHasBookingInProgress;
use Module\Booking\Moderation\Domain\Order\Exception\OrderWithoutBookings;
use Module\Booking\Moderation\Domain\Order\Exception\RefundFeeAmountBelowOrEqualZero;
use Module\Booking\Moderation\Domain\Order\Service\StatusUpdater;
use Module\Booking\Shared\Domain\Order\DbContext\OrderDbContextInterface;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Enum\Order\OrderStatusEnum;

class UpdateStatus implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderDbContextInterface $orderDbContext,
        private readonly StatusUpdater $statusUpdater,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $orderId, int $statusId, ?float $refundFeeAmount): OrderUpdateStatusResponseDto
    {
        $statusEnum = OrderStatusEnum::from($statusId);
        $order = $this->orderRepository->findOrFail(new OrderId($orderId));

        try {
            $this->statusUpdater->update($order, $statusEnum, $refundFeeAmount);
        } catch (OrderHasBookingInProgress $e) {
            throw new OrderHasBookingInProgressException($e);
        } catch (OrderWithoutBookings $e) {
            throw new OrderWithoutBookingsException($e);
        } catch (RefundFeeAmountBelowOrEqualZero) {
            return new OrderUpdateStatusResponseDto(true);
        }

        $this->orderDbContext->store($order);
        $this->eventDispatcher->dispatch(...$order->pullEvents());

        return new OrderUpdateStatusResponseDto();
    }
}
