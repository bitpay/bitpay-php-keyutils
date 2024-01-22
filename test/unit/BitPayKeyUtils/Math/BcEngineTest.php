<?php

declare(strict_types=1);

namespace BitPayKeyUtils\UnitTest\Math;

use BitPayKeyUtils\Math\BcEngine;
use PHPUnit\Framework\TestCase;

class BcEngineTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $bcEngine = $this->createClassObject();
        self::assertInstanceOf(BcEngine::class, $bcEngine);
    }

    public function testAdd(): void
    {
        $expectedValue = '12';

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->add('5', '7'));
    }

    public function testInput(): void
    {
        $expectedValue = '5';

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->input($expectedValue));
    }

    public function testInputNull(): void
    {
        $expectedValue = '0';

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->input(null));
    }

    public function testInputHex(): void
    {
        $expectedValue = '86';

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->input('0x56'));
    }

    public function testInputException(): void
    {
        $this->expectException(\Exception::class);

        $bcEngine = $this->createClassObject();
        $bcEngine->input('Teg4ew');
    }

    public function testCmpGreaterThan(): void
    {
        $expectedValue = 1;

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->cmp('9', '7'));
    }

    public function testCmpLessThan(): void
    {
        $expectedValue = -1;

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->cmp('7', '9'));
    }

    public function testCmpEqualsTo(): void
    {
        $expectedValue = 0;

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->cmp('7', '7'));
    }

    public function testDiv(): void
    {
        $expectedValue = '3';

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->div('6', '2'));
    }

    public function testInvertm(): void
    {
        $expectedValue = '0';

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->invertm('6', '2'));
    }

    public function testInvertm2(): void
    {
        $expectedValue = '1';

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->invertm('-1', '2'));
    }

    public function testMod(): void
    {
        $expectedValue = '0';

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->mod('6', '2'));
    }

    public function testMod2(): void
    {
        $expectedValue = '2';

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->mod('-6', '2'));
    }

    public function testMul(): void
    {
        $expectedValue = '21';

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->mul('3', '7'));
    }

    public function testPow(): void
    {
        $expectedValue = '64';

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->pow('4', '3'));
    }

    public function testSub(): void
    {
        $expectedValue = '18';

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->sub('20', '2'));
    }

    public function testCoprime(): void
    {
        $expectedValue = false;

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->coprime('20', '2'));
    }

    public function testCoprime2(): void
    {
        $expectedValue = true;

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->coprime('5', '3'));
    }

    public function testCoprime3(): void
    {
        $expectedValue = true;

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->coprime('3', '5'));
    }

    public function testCoprime4(): void
    {
        $expectedValue = false;

        $bcEngine = $this->createClassObject();
        self::assertSame($expectedValue, $bcEngine->coprime('3', '3'));
    }

    private function createClassObject()
    {
        return new BcEngine();
    }
}
