<?php

declare(strict_types=1);

namespace BitPayKeyUtils\UnitTest\Util;

use BitPayKeyUtils\Util\Point;
use PHPUnit\Framework\TestCase;

class PointTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $point = $this->createClassObject();
        self::assertInstanceOf(Point::class, $point);
    }

    public function test__toString(): void
    {
        $point = new Point('3', '2');
        self::assertSame('(3, 2)', $point->__toString());
    }

    public function test__toStringInfinite(): void
    {
        $point = new Point('inf', '2');
        self::assertSame('inf', $point->__toString());
    }

    public function testIsInfinityFalse(): void
    {
        $point = $this->createClassObject();
        self::assertFalse($point->isInfinity());
    }

    public function testIsInfinityTrue(): void
    {
        $point = new Point('inf', '4');
        self::assertTrue($point->isInfinity());
    }

    public function testGetX(): void
    {
        $point = $this->createClassObject();
        self::assertSame('-2', $point->getX());
    }

    public function testGetY(): void
    {
        $point = $this->createClassObject();
        self::assertSame('3', $point->getY());
    }

    public function testSerialize(): void
    {
        $expectedValue = 'a:2:{i:0;s:2:"-2";i:1;s:1:"3";}';

        $point = $this->createClassObject();
        self::assertSame($expectedValue, $point->serialize());
    }

    public function testUnserialize(): void
    {
        $expectedValue = '[-2, 3]';
        $testedData = 'a:2:{i:0;s:2:"-2";i:1;s:1:"3";}';

        $point = $this->createClassObject();
        self::assertSame(null, $point->unserialize($testedData));
    }

    public function test__serialize(): void
    {
        $expectedValue = ['-2', '3'];

        $point = $this->createClassObject();
        self::assertSame($expectedValue, $point->__serialize());
    }

    public function test__unserialize(): void
    {
        $expectedValue = ['-2', '3'];

        $point = $this->createClassObject();
        self::assertSame(null, $point->__unserialize(['-2', '3']));
    }

    private function createClassObject(): Point
    {
        return new Point('-2', '3');
    }
}
