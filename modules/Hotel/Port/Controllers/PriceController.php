<?php

namespace Module\Hotel\Port\Controllers;

use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Port\Request;
use Module\Hotel\Application\Command\SetSeasonPrice;

class PriceController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {}

    public function setSeasonPrice(Request $request): bool
    {
        $request->validate([
            'hotelId' => 'required|int',
        ]);

        return $this->commandBus->execute(new SetSeasonPrice($request->hotelId, $request->ids));
    }
}
