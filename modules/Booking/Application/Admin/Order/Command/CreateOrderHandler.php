<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Order\Command;

use Module\Booking\Domain\Order\Repository\OrderRepositoryInterface;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class CreateOrderHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository
    ) {}

    public function handle(CommandInterface|CreateOrder $command): int
    {
        $order = $this->repository->create($command->clientId, $command->currency, $command->legalId);

        //@todo ивенты созданного заказа
        return $order->id()->value();
    }
}
