<?php

namespace GTS\Shared\Application\Command;

interface CommandBusInterface
{

    public function execute(CommandInterface $command): mixed;

}
