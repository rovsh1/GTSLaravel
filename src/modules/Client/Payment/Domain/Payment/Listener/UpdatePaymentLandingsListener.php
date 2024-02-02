<?php

declare(strict_types=1);

namespace Module\Client\Payment\Domain\Payment\Listener;

use Module\Client\Payment\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Payment\Domain\Payment\Repository\PaymentRepositoryInterface;
use Module\Client\Payment\Domain\Payment\ValueObject\Landing;
use Module\Client\Payment\Domain\Payment\ValueObject\LandingCollection;
use Module\Client\Shared\Domain\ValueObject\OrderId;
use Sdk\Booking\IntegrationEvent\OrderRefunded;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventListenerInterface;
use Sdk\Shared\Enum\Order\OrderStatusEnum;
use Sdk\Shared\Event\IntegrationEventMessage;

class UpdatePaymentLandingsListener implements IntegrationEventListenerInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly PaymentRepositoryInterface $paymentRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function handle(IntegrationEventMessage $message): void
    {
        $event = $message->event;
        assert($event instanceof OrderRefunded);

        $order = $this->orderRepository->findOrFail(new OrderId($event->orderId));
        $payments = $this->paymentRepository->findByOrderId($order->id());

        foreach ($payments as $payment) {
            $landings = array_filter(
                $payment->landings()->all(),
                fn(Landing $landing) => !$landing->orderId()->isEqual($order->id())
            );

            if ($order->status() === OrderStatusEnum::REFUND_FEE) {
                $landings[] = new Landing($order->id(), $order->clientPenalty()->value());
            }

            $payment->setLandings(new LandingCollection($landings));
            $this->paymentRepository->store($payment);
            $this->eventDispatcher->dispatch(...$payment->pullEvents());
        }
    }
}
