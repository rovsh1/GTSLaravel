<?php

declare(strict_types=1);

namespace Module\Catalog\Application\Admin\UseCase\Price;

use Carbon\CarbonInterface;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetDatePrice implements UseCaseInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public function execute(
        CarbonInterface $date,
        int $seasonId,
        int $roomId,
        int $rateId,
        int $guestsCount,
        bool $isResident,
        ?float $price
    ): void {
        $this->commandBus->execute(
            new \Module\Catalog\Application\Admin\Command\Price\Date\Set(
                date: $date,
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
