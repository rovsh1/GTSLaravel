<?php

namespace Module\Hotel\Application\Query\Price\Date;

use Module\Hotel\Application\Factory\PriceDtoFactory;
use Module\Hotel\Infrastructure\Models\DatePrice;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class GetHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface|Get $query): mixed
    {
        $prices = DatePrice::whereSeasonId($query->seasonId)->withGroup()->get();

        return PriceDtoFactory::collection($prices);
    }
}
