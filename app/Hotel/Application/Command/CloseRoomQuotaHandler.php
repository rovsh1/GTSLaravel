<?php

namespace GTS\Hotel\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;

class CloseRoomQuotaHandler implements CommandHandlerInterface
{
    /**
     * @param CloseRoomQuota $command
     * @return void
     */
    public function handle(CommandInterface $command){

    }
}
