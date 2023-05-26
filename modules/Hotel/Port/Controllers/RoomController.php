<?php

declare(strict_types=1);

namespace Module\Hotel\Port\Controllers;

use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Custom\Framework\PortGateway\Request;
use Module\Hotel\Application\Query\GetRoomById;

class RoomController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {}


    public function getRoom(Request $request): mixed
    {
        $request->validate([
            'id' => ['required', 'int'],
        ]);

        return $this->queryBus->execute(new GetRoomById($request->id));
    }
}
