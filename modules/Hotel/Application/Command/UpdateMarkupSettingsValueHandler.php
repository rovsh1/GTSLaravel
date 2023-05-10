<?php

namespace Module\Hotel\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;

class UpdateMarkupSettingsValueHandler implements CommandHandlerInterface
{

    /**
     * @param UpdateMarkupSettingsValue $command
     * @return int|string|null|void
     */
    public function handle(CommandInterface|UpdateMarkupSettingsValue $command)
    {
        // TODO: Implement handle() method.
    }
}
