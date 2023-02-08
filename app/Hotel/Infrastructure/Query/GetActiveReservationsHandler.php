<?php

namespace GTS\Hotel\Infrastructure\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;

class GetActiveReservationsHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface $query)
    {
    }
}
