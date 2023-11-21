<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order;

use Module\Booking\Moderation\Application\Dto\OrderAvailableActionsDto;
use Module\Booking\Moderation\Application\Factory\OrderStatusDtoFactory;
use Module\Booking\Moderation\Domain\Order\Service\StatusRules\AdministratorRules;
use Module\Booking\Shared\Application\Dto\StatusDto;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Shared\Enum\Order\OrderStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableActions implements UseCaseInterface
{
    public function __construct(
        private readonly AdministratorRules $statusRules,
        private readonly OrderRepositoryInterface $repository,
        private readonly OrderStatusDtoFactory $statusDtoFactory,
    ) {}

    public function execute(int $orderId): OrderAvailableActionsDto
    {
        $order = $this->repository->findOrFail(new OrderId($orderId));
        $statuses = $this->getAvailableStatuses($order);

        return new OrderAvailableActionsDto(
            $statuses,
            $this->statusRules->isEditableStatus($order->status()),
            true,
            true,
            true,
            true,
        );
    }

    /**
     * @param Order $order
     * @return StatusDto[]
     */
    private function getAvailableStatuses(Order $order): array
    {
        $statuses = $this->statusRules->getStatusTransitions($order->status());
        $statusIds = array_flip(array_map(fn(OrderStatusEnum $status) => $status->value, $statuses));
        $statusSettings = $this->statusDtoFactory->statuses();

        return array_values(
            array_filter($statusSettings, fn(StatusDto $statusDto) => array_key_exists($statusDto->id, $statusIds))
        );
    }
}
