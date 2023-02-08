<?php

namespace Custom\Framework\Contracts\Bus;

interface CommandBusInterface
{

    public function execute(CommandInterface $command): mixed;

}
