<?php

namespace Module\Hotel\Port\Controllers;

use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Custom\Framework\PortGateway\Request;
use Module\Hotel\Application\Command;
use Module\Hotel\Application\Query;

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
            'guestsNumber' => 'required|int',
            'isResident' => 'required|boolean',
            'price' => 'required|numeric',
            'currencyId' => 'required|int',
        ]);
        return $this->commandBus->execute(
            new Command\Price\Season\Set(
                seasonId: $request->seasonId,
                roomId: $request->roomId,
                rateId: $request->rateId,
                guestsNumber: $request->guestsNumber,
                isResident: $request->isResident,
                price: $request->price,
                currencyId: $request->currencyId
            )
        );
    }

    public function getDatePrices(Request $request): array
    {
        $request->validate([
            'seasonId' => 'required|int',
        ]);

        return $this->queryBus->execute(
            new Query\Price\Date\Get($request->seasonId),
        );
    }

    public function setDatePrice(Request $request): bool
    {
        $request->validate([
            'seasonId' => 'required|int',
            'roomId' => 'required|int',
            'rateId' => 'required|int',
            'guestsNumber' => 'required|int',
            'isResident' => 'required|boolean',
            'price' => 'required|numeric',
            'currencyId' => 'required|int',
            'date' => ['required', 'date']
        ]);

        return $this->commandBus->execute(
            new Command\Price\Date\Set(
                date: $request->date,
                seasonId: $request->seasonId,
                roomId: $request->roomId,
                rateId: $request->rateId,
                guestsNumber: $request->guestsNumber,
                isResident: $request->isResident,
                price: $request->price,
                currencyId: $request->currencyId
            )
        );
    }
}

