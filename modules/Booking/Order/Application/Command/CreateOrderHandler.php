<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\Command;

use Module\Booking\Order\Infrastructure\Models\Order;
use Module\Booking\Order\Infrastructure\Models\OrderStatusEnum;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class CreateOrderHandler implements CommandHandlerInterface
{
    public function __construct(

    ) {}

    public function handle(CommandInterface|CreateOrder $command): int
    {
        $order = Order::create([
            'status' => OrderStatusEnum::NEW,
            'client_id' => $command->clientId,
            'legal_id' => $command->legalId,
            'currency_id' => $command->currencyId
        ]);
        //@todo ивенты созданного заказа
        return $order->id;
    }
}
