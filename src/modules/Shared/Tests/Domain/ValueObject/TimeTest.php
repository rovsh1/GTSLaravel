<?php

namespace Module\Shared\Tests\Domain\ValueObject;

use Module\Shared\ValueObject\Time;
use PHPUnit\Framework\TestCase;

class TimeTest extends TestCase
{
    /**
     * @dataProvider validTimeProvider
     */
    public function testValidTime(string $time)
    {
        $object = new Time($time);
        $this->assertEquals($time, $object->value());
    }

    public static function validTimeProvider() {
        return [
            ['00:00'],
            ['12:00'],
            ['14:00'],
            ['24:00'],
        ];
    }

    /**
     * @dataProvider invalidTimeProvider
     */
    public function testInvalidTime(string $time)
    {
        $this->expectException(\InvalidArgumentException::class);
        new Time($time);
    }

    public static function invalidTimeProvider()
    {
        return [
            ['25:00'],
            ['12:68'],
        ];
    }
}
