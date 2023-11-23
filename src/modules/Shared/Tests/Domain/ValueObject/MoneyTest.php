<?php

namespace Module\Shared\Tests\Domain\ValueObject;

use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testIsEqual(Money $a, Money $b, bool $result)
    {
        $this->assertEquals($a->isEqual($b), $result);
    }

    public static function dataProvider(): array
    {
        return [
            [new Money(CurrencyEnum::UZS, 1), new Money(CurrencyEnum::UZS, 1), true],
            [new Money(CurrencyEnum::UZS, 1), new Money(CurrencyEnum::UZS, 1.0001), true],
            [new Money(CurrencyEnum::UZS, 1), new Money(CurrencyEnum::UZS, 1.01), false],
            [new Money(CurrencyEnum::UZS, 1.001), new Money(CurrencyEnum::UZS, 1.01), false],
            [new Money(CurrencyEnum::UZS, 1.00100103123123), new Money(CurrencyEnum::UZS, 1), false],
            [new Money(CurrencyEnum::UZS, 1), new Money(CurrencyEnum::USD, 1), false],
            [new Money(CurrencyEnum::USD, 1.00100103123123), new Money(CurrencyEnum::USD, 1.00100103123123), true],
        ];
    }
}
