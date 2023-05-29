<?php

namespace Sdk\Module\Contracts\Bus;

interface CommandHandlerInterface
{
    /**
     * @param CommandInterface $command
     * @return mixed|void
     */
    public function handle(CommandInterface $command);
}
