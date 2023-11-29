<?php

declare(strict_types=1);

namespace Module\Client\Payment\Domain\Order\Listener;

use Module\Client\Payment\Domain\Order\Order;
use Module\Client\Payment\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Payment\Domain\Payment\Event\PaymentLandingsModified;
use Module\Client\Payment\Domain\Payment\ValueObject\Landing;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class UpdateOrderStatus implements DomainEventListenerInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository
    ) {}

    public function handle(DomainEventInterface $event)
    {
        assert($event instanceof PaymentLandingsModified);

        /** @var Order[] $currentOrders */
        $currentOrders = [];
        foreach ($event->landings as $landing) {
            $order = $this->orderRepository->findOrFail($landing->orderId());
            $currentOrders[$order->id()->value()] = $order;
            $this->processOrder($order);
        }

        /** @var Landing[] $removedOrderLandings */
        $removedOrderLandings = collect($event->oldLandings)->filter(
            fn(Landing $landing) => !array_key_exists($landing->orderId()->value(), $currentOrders)
        );

        foreach ($removedOrderLandings as $landing) {
            $order = $this->orderRepository->findOrFail($landing->orderId());
            $this->processOrder($order);
        }
    }

    private function processOrder(Order $order): void
    {
        if ($order->payedAmount()->isZero()) {
            $order->invoiced();
            $this->orderRepository->store($order);

            return;
        }

        if ($order->payedAmount()->isEqual($order->clientPrice())) {
            $order->paid();
        } else {
            $order->partialPaid();
        }

        $this->orderRepository->store($order);
    }
}
