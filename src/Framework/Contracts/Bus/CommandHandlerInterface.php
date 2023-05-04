<?php

namespace Custom\Framework\Contracts\Bus;

interface CommandHandlerInterface
{
    /**
     * @param CommandInterface $command
     * @return int|string|null|void
     */
    public function handle(CommandInterface $command): mixed;
}
