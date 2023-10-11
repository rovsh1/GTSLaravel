<?php

namespace Module\Shared\Tests\Domain\ValueObject;

use Module\Shared\ValueObject\Percent;
use PHPUnit\Framework\TestCase;

class PercentTest extends TestCase
{
    public function testPercentCantBeNegative()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Percent(-100);
    }
}
