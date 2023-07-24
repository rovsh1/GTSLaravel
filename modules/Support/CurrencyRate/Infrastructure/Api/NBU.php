<?php

namespace Module\Support\CurrencyRate\Infrastructure\Api;

use DateTime;
use Exception;
use Module\Support\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;

class NBU extends AbstractApi
{
    private const URL = 'http://cbu.uz/ru/arkhiv-kursov-valyut/';

    /**
     * @throws Exception
     */
    public function getRates(\DateTime $date = null): CurrencyRatesCollection
    {
        $xml = $this->xmlRequest(self::URL . 'xml/all/' . static::dateFormat($date ?? new DateTime()) . '/');

        return $this->makeCollectionFromXmlPath(
            $xml->xpath('CcyNtry'),
            'Ccy',
            'Rate',
            'Nominal'
        );
    }

    private static function dateFormat(DateTime $date): string
    {
        return $date->format('Y-m-d');
    }
}