<?php

declare(strict_types=1);

namespace Module\Booking\Notification\Application\UseCase;

use Module\Booking\Moderation\Application\Dto\VoucherDto;
use Module\Booking\Moderation\Application\Factory\VoucherDtoFactory;
use Module\Booking\Notification\Domain\Factory\VoucherFactory;
use Module\Booking\Shared\Domain\Order\DbContext\OrderDbContextInterface;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CreateVoucher implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderDbContextInterface $orderDbContext,
        private readonly VoucherFactory $voucherFactory,
        private readonly VoucherDtoFactory $voucherDtoFactory,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $orderId): VoucherDto
    {
        $order = $this->orderRepository->findOrFail(new OrderId($orderId));
        $voucher = $this->voucherFactory->build($order);
        $order->setVoucher($voucher);

        $this->orderDbContext->store($order);
        $this->eventDispatcher->dispatch(...$order->pullEvents());

        return $this->voucherDtoFactory->createFromEntity($voucher);
    }
}
