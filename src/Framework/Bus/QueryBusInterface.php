<?php

namespace Custom\Framework\Bus;

interface QueryBusInterface
{
    public function execute(QueryInterface $query): mixed;
}
