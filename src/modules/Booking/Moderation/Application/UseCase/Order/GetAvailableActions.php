<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order;

use Module\Booking\Moderation\Application\Dto\OrderAvailableActionsDto;
use Module\Booking\Moderation\Application\Factory\OrderStatusDtoFactory;
use Module\Booking\Moderation\Domain\Order\Service\StatusTransitionRules;
use Module\Booking\Shared\Application\Dto\StatusDto;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Shared\Enum\Order\OrderStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableActions implements UseCaseInterface
{
    public function __construct(
        private readonly StatusTransitionRules $statusTransitionRules,
        private readonly OrderRepositoryInterface $repository,
        private readonly OrderStatusDtoFactory $statusDtoFactory,
    ) {
    }

    public function execute(int $orderId): OrderAvailableActionsDto
    {
        $order = $this->repository->findOrFail(new OrderId($orderId));

        return new OrderAvailableActionsDto(
            statuses: $this->buildAvailableStatuses($order),
            isEditable: $order->inModeration(),
            canSendVoucher: true,
            canCreateInvoice: true,
            canSendInvoice: true,
            canCancelInvoice: true,
        );
    }

    /**
     * @param Order $order
     * @return StatusDto[]
     */
    private function buildAvailableStatuses(Order $order): array
    {
        return array_map(
            fn(OrderStatusEnum $s) => $this->statusDtoFactory->get($s),
            $this->statusTransitionRules->getAvailableTransitions($order->status())
        );
    }
}
