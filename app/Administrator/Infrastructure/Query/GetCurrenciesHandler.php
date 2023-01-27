<?php

namespace GTS\Administrator\Infrastructure\Query;

use GTS\Shared\Application\Query\QueryHandlerInterface;
use GTS\Shared\Application\Query\QueryInterface;

use GTS\Administrator\Infrastructure\Models\Currency;

class GetCurrenciesHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface $query): array
    {
        dd($query);
        $currencies = Currency::all();

        return [
            new \stdClass(),
            new \stdClass()
        ];
        // Должен вернуть массив из DTO
    }
}
