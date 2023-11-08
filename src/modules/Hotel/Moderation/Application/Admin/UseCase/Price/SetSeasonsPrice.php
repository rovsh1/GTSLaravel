<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Admin\UseCase\Price;

use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetSeasonsPrice implements UseCaseInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public function execute(
        int $seasonId,
        int $roomId,
        int $rateId,
        int $guestsCount,
        bool $isResident,
        ?float $price
    ): void {
        $this->commandBus->execute(
            new \Module\Hotel\Moderation\Application\Admin\Command\Price\Season\Set(
                seasonId: $seasonId,
                roomId: $roomId,
                rateId: $rateId,
                guestsCount: $guestsCount,
                isResident: $isResident,
                price: $price,
            )
        );
    }
}
