<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order\Voucher;

use Module\Booking\Moderation\Domain\Order\Factory\VoucherFactory;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CreateVoucher implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly VoucherFactory $voucherFactory,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $orderId): void
    {
        $order = $this->orderRepository->findOrFail(new OrderId($orderId));
        $voucher = $this->voucherFactory->build($order->id());
        $order->setVoucher($voucher);

        $this->orderRepository->store($order);
        $this->eventDispatcher->dispatch(...$order->pullEvents());
    }
}
