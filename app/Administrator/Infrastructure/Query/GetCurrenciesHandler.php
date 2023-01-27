<?php

namespace GTS\Administrator\Infrastructure\Query;

use GTS\Shared\Application\Query\QueryHandlerInterface;
use GTS\Shared\Application\Query\QueryInterface;

use GTS\Administrator\Infrastructure\Models\Currency;

class GetCurrenciesHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface $query): array
    {
        //dd($query);
        //$currencies = Currency::all();

        return [
            (object)[
                'id' => 1,
                'code_num' => 860,
                'code_char' => 'UZS',
                'sign' => 'сўм',
                'rate' => 1.00
            ],
            (object)[
                'id' => 3,
                'code_num' => 840,
                'code_char' => 'USD',
                'sign' => '$',
                'rate' => 11320.02
            ]
        ];
        // Должен вернуть массив из DTO
    }
}
