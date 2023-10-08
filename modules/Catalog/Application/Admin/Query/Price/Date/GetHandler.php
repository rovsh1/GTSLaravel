<?php

namespace Module\Catalog\Application\Admin\Query\Price\Date;

use Module\Catalog\Application\Admin\Factory\PriceDtoFactory;
use Module\Catalog\Infrastructure\Models\DatePrice;
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
