<?php

namespace Custom\Framework\Bus;

interface CommandBusInterface
{

    public function execute(CommandInterface $command): mixed;

}
