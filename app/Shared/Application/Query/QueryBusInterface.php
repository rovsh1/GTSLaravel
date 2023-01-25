<?php

namespace GTS\Shared\Application\Query;

interface QueryBusInterface
{
    public function execute(QueryInterface $query);
}
