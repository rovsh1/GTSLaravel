<?php

namespace Sdk\Module\Contracts\Bus;

interface CommandBusInterface
{

    public function execute(CommandInterface $command): mixed;

}
