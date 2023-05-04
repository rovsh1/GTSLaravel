<?php

declare(strict_types=1);

namespace Module\Hotel\Port\Controllers;

use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Custom\Framework\PortGateway\Request;
use Module\Hotel\Application\Command\UpdateClientMarkups;
use Module\Hotel\Application\Query\GetHotelMarkupSettings;

class MarkupController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function getHotelMarkupSettings(Request $request): mixed
    {
        $request->validate([
            'hotel_id' => 'required|numeric',
        ]);

        return $this->queryBus->execute(new GetHotelMarkupSettings($request->hotel_id));
    }

    public function updateClientMarkups(Request $request): mixed
    {
        $request->validate([
            'hotel_id' => ['required', 'numeric'],
            'individual' => ['required_without_all:OTA,TA,TO', 'nullable', 'integer'],
            'OTA' => ['required_without_all:individual,TA,TO', 'nullable', 'integer'],
            'TA' => ['required_without_all:individual,OTA,TO', 'nullable', 'integer'],
            'TO' => ['required_without_all:individual,OTA,TA', 'nullable', 'integer'],
        ]);

        return $this->commandBus->execute(
            new UpdateClientMarkups(
                hotelId: $request->hotel_id,
                individual: $request->individual,
                OTA: $request->OTA,
                TA: $request->TA,
                TO: $request->TO,
            )
        );
    }
}
