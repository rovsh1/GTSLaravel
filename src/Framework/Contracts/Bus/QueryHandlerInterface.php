<?php

namespace Custom\Framework\Contracts\Bus;

interface QueryHandlerInterface
{
    public function handle(QueryInterface $query): mixed;
}
