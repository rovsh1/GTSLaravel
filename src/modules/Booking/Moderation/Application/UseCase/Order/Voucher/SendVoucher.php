<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order\Voucher;

use Module\Booking\Moderation\Domain\Order\Factory\VoucherFactory;
use Module\Booking\Moderation\Domain\Order\ValueObject\Voucher;
use Module\Booking\Shared\Domain\Order\DbContext\OrderDbContextInterface;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SendVoucher implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderDbContextInterface $orderDbContext,
        private readonly VoucherFactory $voucherFactory,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $orderId): void
    {
        $order = $this->orderRepository->findOrFail(new OrderId($orderId));

        $voucher = $order->voucher();
        if ($order->timestamps()->updatedAt()->getTimestamp() > $voucher?->createdAt()->getTimestamp()) {
            //@todo всегда выполняется, т.к. ваучер записывается в заказ и поле updated_at обновляется
            $voucher = $this->voucherFactory->build($order);
        }

        //@todo отправка ваучера по емейлу

        $voucher = new Voucher($voucher->createdAt(), $voucher->file(), now()->toDateTimeImmutable());
        $order->setVoucher($voucher);
        $this->orderDbContext->store($order);
        $this->eventDispatcher->dispatch(...$order->pullEvents());
    }
}
