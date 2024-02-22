<?php

namespace Module\Shared\Tests\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use Sdk\Shared\ValueObject\Percent;

class PercentTest extends TestCase
{
    public function testPercentCantBeNegative()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Percent(-100);
    }
}
