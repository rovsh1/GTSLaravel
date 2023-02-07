<?php

namespace Custom\Framework\Contracts\Bus;

interface QueryBusInterface
{
    public function execute(QueryInterface $query): mixed;
}
