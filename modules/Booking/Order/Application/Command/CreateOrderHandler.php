<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\Command;

use Module\Booking\Order\Infrastructure\Model\Order;
use Module\Booking\Order\Infrastructure\Model\OrderStatusEnum;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class CreateOrderHandler implements CommandHandlerInterface
{
    public function handle(CommandInterface|CreateOrder $command): int
    {
        $order = Order::create([
            'status' => OrderStatusEnum::NEW,
            'client_id' => $command->clientId,
        ]);
        //@todo ивенты созданного заказа
        return $order->id;
    }
}
