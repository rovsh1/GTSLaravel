<?php

namespace Sdk\Module\Contracts\Bus;

interface QueryHandlerInterface
{
    public function handle(QueryInterface $query): mixed;
}
