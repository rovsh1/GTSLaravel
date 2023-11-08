<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order;

use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Shared\Enum\Booking\OrderStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateStatus implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository,
    ) {}

    public function execute(int $orderId, int $statusId,): void
    {
        $order = $this->repository->findOrFail(new OrderId($orderId));
        $statusEnum = OrderStatusEnum::from($statusId);

        switch ($statusEnum) {
            case OrderStatusEnum::WAITING_INVOICE:
//                $this->statusUpdater->toProcessing($booking);
                break;
            case OrderStatusEnum::INVOICED:
//                $this->statusUpdater->cancel($booking);
                break;
            case OrderStatusEnum::PARTIAL_PAID:
//                $this->statusUpdater->cancel($booking);
                break;
            case OrderStatusEnum::PAID:
//                $this->statusUpdater->cancel($booking);
                break;
        }
    }
}
