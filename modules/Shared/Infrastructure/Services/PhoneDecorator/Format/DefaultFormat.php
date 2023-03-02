<?php

namespace Module\Shared\Infrastructure\Services\PhoneDecorator\Format;

class DefaultFormat implements FormatInterface
{
    protected string $countryCode = '';

    public function __construct(public readonly ?string $number) {}

    public function getCountryCode(string $number): string
    {
        return $this->countryCode;
    }

    public function getAreaCode(string $number): string
    {
        return substr($number, strlen($this->countryCode), -7);
    }

    public function getNumber(string $number): string
    {
        return substr($number, -7, 3)
            . '-' . substr($number, -4, 2)
            . '-' . substr($number, -2, 2);
    }

    public function toText(string $number): string
    {
        return '+' . $this->countryCode
            . (($areaCode = $this->getAreaCode($number)) ? ' ' . $areaCode : '')
            . ' ' . $this->getNumber($number);
    }
}
