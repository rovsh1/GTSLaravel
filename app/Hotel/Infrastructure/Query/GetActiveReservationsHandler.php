<?php

namespace GTS\Hotel\Infrastructure\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;

use GTS\Hotel\Application\Query\GetActiveReservations;

class GetActiveReservationsHandler implements QueryHandlerInterface
{
    /**
     * @param GetActiveReservations $query
     * @return void
     */
    public function handle(QueryInterface $query)
    {
    }
}
