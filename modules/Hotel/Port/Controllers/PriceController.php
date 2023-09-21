<?php

namespace Module\Hotel\Port\Controllers;

use Module\Hotel\Application\Command;
use Module\Hotel\Application\Query;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\PortGateway\Request;

class PriceController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus
    ) {}

    public function getSeasonsPrices(Request $request): mixed
    {
        $request->validate([
            'hotelId' => 'required|int',
        ]);

        return $this->queryBus->execute(
            new Query\Price\Season\Get($request->hotelId),
        );
    }

    public function setSeasonPrice(Request $request): bool
    {
        $request->validate([
            'seasonId' => 'required|int',
            'roomId' => 'required|int',
            'rateId' => 'required|int',
            'guestsCount' => 'required|int',
            'isResident' => 'required|boolean',
            'price' => 'nullable|numeric',
        ]);
        return $this->commandBus->execute(
            new Command\Price\Season\Set(
                seasonId: $request->seasonId,
                roomId: $request->roomId,
                rateId: $request->rateId,
                guestsCount: $request->guestsCount,
                isResident: $request->isResident,
                price: $request->price,
            )
        );
    }

    public function setDatePrice(Request $request): bool
    {
        $request->validate([
            'seasonId' => 'required|int',
            'roomId' => 'required|int',
            'rateId' => 'required|int',
            'guestsCount' => 'required|int',
            'isResident' => 'required|boolean',
            'price' => 'required|numeric',
            'date' => ['required', 'date']
        ]);

        return $this->commandBus->execute(
            new Command\Price\Date\Set(
                date: $request->date,
                seasonId: $request->seasonId,
                roomId: $request->roomId,
                rateId: $request->rateId,
                guestsCount: $request->guestsCount,
                isResident: $request->isResident,
                price: $request->price,
            )
        );
    }
}

