<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Booking\Order\Infrastructure\Model\Order;
use Module\Booking\Order\Infrastructure\Model\OrderStatusEnum;

class CreateOrderHandler implements CommandHandlerInterface
{
    public function handle(CommandInterface|CreateOrder $command): int
    {
        $order = Order::create([
            'status' => OrderStatusEnum::NEW,
            'client_id' => $command->clientId,
        ]);
        return $order->id;
    }
}
