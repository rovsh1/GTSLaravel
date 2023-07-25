<?php

namespace Module\Generic\CurrencyRate\Infrastructure\Api;

use DateTime;
use Exception;
use Module\Generic\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;

class CBRU extends AbstractApi
{
    private const DAILY_URL = 'http://www.cbr.ru/scripts/XML_daily.asp';

    /**
     * @throws Exception
     */
    public function getRates(DateTime $date = null): CurrencyRatesCollection
    {
        $xml = $this->xmlRequest(self::DAILY_URL, ['date_req' => static::dateFormat($date ?? new DateTime())]);

        return $this->makeCollectionFromXmlPath(
            $xml->xpath('Valute'),
            'CharCode',
            'Value',
            'Nominal'
        );
    }

    private static function dateFormat(DateTime $date): string
    {
        return $date->format('d/m/Y');
    }
}