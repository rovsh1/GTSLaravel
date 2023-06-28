<?php

namespace Module\Pricing\CurrencyRate\Infrastructure\Api;

use Exception;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyRate;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;
use Module\Shared\Enum\CurrencyEnum;
use SimpleXMLElement;

abstract class AbstractApi implements ApiInterface
{
    private const TIMEOUT = 5;

    /**
     * @throws Exception
     */
    protected function xmlRequest(string $url, array $params = []): SimpleXMLElement
    {
        $response = $this->request($url, $params);

        return $this->validateXmlResponse($response);
    }

    protected function validateXmlResponse(?string $response): SimpleXMLElement
    {
        try {
            return simplexml_load_string($response);
        } catch (Exception $e) {
            throw new Exception('Cant parse xml data', null, $e);
        }
    }

    protected function makeCollectionFromXmlPath(
        array $path,
        string $currencyCode,
        string $rateKey,
        string $nominalKey
    ): CurrencyRatesCollection {
        $rates = [];
        foreach ($path as $r) {
            $currency = CurrencyEnum::tryFrom(strtoupper((string)$r->$currencyCode));
            if (null === $currency) {
                continue;
            }
            $rates[] = new CurrencyRate(
                $currency,
                self::rateFromString((string)$r->$rateKey),
                (int)(string)$r->$nominalKey
            );
        }

        return new CurrencyRatesCollection($rates);
    }

    /**
     * @throws Exception
     */
    protected function request(string $url, array $params = []): ?string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . ($params ? '?' . http_build_query($params) : ''));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::TIMEOUT);
        $out = curl_exec($ch);

        $this->validateCurl($ch);

        curl_close($ch);

        return $out;
    }

    protected function validateCurl($ch): void
    {
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (200 !== $httpCode) {
            $errorNo = curl_errno($ch);
            $error = curl_error($ch);
            curl_close($ch);

            throw new Exception($httpCode . ': ' . ($errorNo ? $error : static::class . ' unavailable'));
        }
    }

    protected static function rateFromString(string $rate): float
    {
        return (float)str_replace(',', '.', $rate);
    }
}