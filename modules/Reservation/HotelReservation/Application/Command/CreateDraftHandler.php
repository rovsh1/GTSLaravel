<?php

namespace Module\Reservation\HotelReservation\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;

class CreateDraftHandler implements CommandHandlerInterface
{
    public function handle(CommandInterface $command) {}
}
