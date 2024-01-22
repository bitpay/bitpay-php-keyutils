<?php

declare(strict_types=1);

namespace BitPayKeyUtils\UnitTest\Math;

use BitPayKeyUtils\Math\GmpEngine;
use BitPayKeyUtils\Math\Math;
use PHPUnit\Framework\TestCase;

class MathTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $math = $this->createClassObject();
        self::assertInstanceOf(Math::class, $math);
    }

    public function testGetEngine(): void
    {
        $expectedEngine = new GmpEngine();

        $math = $this->createClassObject();
        $math::setEngine($expectedEngine);
        self::assertSame($expectedEngine, $math::getEngine());
    }

    public function testGetEngineName(): void
    {
        $expectedEngineName = 'Test engine name';

        $math = $this->createClassObject();
        $math::setEngineName($expectedEngineName);
        self::assertSame($expectedEngineName, $math::getEngineName());
    }

    private function createClassObject(): Math
    {
        return new Math();
    }
}
