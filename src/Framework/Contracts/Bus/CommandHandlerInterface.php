<?php

namespace Custom\Framework\Contracts\Bus;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command);
}
