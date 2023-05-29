<?php

namespace Sdk\Module\Contracts\Bus;

interface QueryBusInterface
{
    public function execute(QueryInterface $query): mixed;
}
