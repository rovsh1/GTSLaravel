<?php

namespace Module\Shared\Infrastructure\Service\PhoneDecorator\Format;

interface FormatInterface
{
    public function getCountryCode(string $number): string;

    public function getAreaCode(string $number): string;

    public function getNumber(string $number): string;
}
