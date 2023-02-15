<?php

namespace GTS\Hotel\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;

class OpenRoomQuotaHandler implements CommandHandlerInterface
{
    /**
     * @param OpenRoomQuota $command
     * @return void
     */
    public function handle(CommandInterface $command) {}
}
