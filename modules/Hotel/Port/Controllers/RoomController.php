<?php

declare(strict_types=1);

namespace Module\Hotel\Port\Controllers;

use Module\Hotel\Application\Query\GetRoomById;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\PortGateway\Request;

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
