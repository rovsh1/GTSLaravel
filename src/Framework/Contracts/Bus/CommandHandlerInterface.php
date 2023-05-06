<?php

namespace Custom\Framework\Contracts\Bus;

interface CommandHandlerInterface
{
    /**
     * @param CommandInterface $command
     * @return mixed|void
     */
    public function handle(CommandInterface $command);
}
