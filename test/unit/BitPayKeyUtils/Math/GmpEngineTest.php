<?php

declare(strict_types=1);

namespace BitPayKeyUtils\UnitTest\Math;

use BitPayKeyUtils\Math\GmpEngine;
use PHPUnit\Framework\TestCase;

class GmpEngineTest extends TestCase
{
    public function testAdd(): void
    {
        $expectedResult = '5';
        $gmp = $this->createClassObject();
        $actualResult = $gmp->add('2', '3');

        self::assertIsString($actualResult);
        self::assertSame($expectedResult, $actualResult);
    }

    public function testCmpGreaterThan(): void
    {
        $expectedResult = '1';
        $gmp = $this->createClassObject();
        $actualResult = $gmp->cmp('1234', '1000');

        self::assertIsString($actualResult);
        self::assertSame($expectedResult, $actualResult);
    }

    public function testCmpLessThan(): void
    {
        $expectedResult = '-1';
        $gmp = $this->createClassObject();
        $actualResult = $gmp->cmp('1000', '1234');

        self::assertIsString($actualResult);
        self::assertSame($expectedResult, $actualResult);
    }

    public function testCmpEqualTo(): void
    {
        $expectedResult = '0';
        $gmp = $this->createClassObject();
        $actualResult = $gmp->cmp('1000', '1000');

        self::assertIsString($actualResult);
        self::assertSame($expectedResult, $actualResult);
    }

    public function testDiv(): void
    {
        $expectedResult = '2';
        $gmp = $this->createClassObject();
        $actualResult = $gmp->div('10', '5');

        self::assertIsString($actualResult);
        self::assertSame($expectedResult, $actualResult);
    }

    public function testInvertm(): void
    {
        $expectedResult = '9';
        $gmp = $this->createClassObject();
        $actualResult = $gmp->invertm('5', '11');

        self::assertIsString($actualResult);
        self::assertSame($expectedResult, $actualResult);
    }

    public function testMod(): void
    {
        $expectedResult = '3';
        $gmp = $this->createClassObject();
        $actualResult = $gmp->mod('7', '4');

        self::assertIsString($actualResult);
        self::assertSame($expectedResult, $actualResult);
    }

    public function testMul(): void
    {
        $expectedResult = '16';
        $gmp = $this->createClassObject();
        $actualResult = $gmp->mul('8', '2');

        self::assertIsString($actualResult);
        self::assertSame($expectedResult, $actualResult);
    }

    public function testPow(): void
    {
        $expectedResult = '27';
        $gmp = $this->createClassObject();
        $actualResult = $gmp->pow('3', '3');

        self::assertIsString($actualResult);
        self::assertSame($expectedResult, $actualResult);
    }

    public function testSub(): void
    {
        $expectedResult = '42';
        $gmp = $this->createClassObject();
        $actualResult = $gmp->sub('64', '22');

        self::assertIsString($actualResult);
        self::assertSame($expectedResult, $actualResult);
    }

    private function createClassObject(): GmpEngine
    {
        return new GmpEngine();
    }
}
