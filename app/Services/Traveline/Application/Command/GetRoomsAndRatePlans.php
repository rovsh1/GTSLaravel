<?php

namespace GTS\Services\Traveline\Application\Command;

use GTS\Shared\Application\Command\CommandInterface;

class GetRoomsAndRatePlans implements CommandInterface
{
    public function __construct(public readonly int $hotelId)
    {
    }
}
