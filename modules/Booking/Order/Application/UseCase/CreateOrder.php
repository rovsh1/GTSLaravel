<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\UseCase;

use Module\Booking\Order\Application\Command\CreateOrder as Command;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CreateOrder implements UseCaseInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {}

    public function execute(int $clientId): int
    {
        return $this->commandBus->execute(new Command($clientId));
    }
}
