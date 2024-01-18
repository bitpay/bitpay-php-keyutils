<?php

declare(strict_types=1);

namespace BitPayKeyUtils\UnitTest\Util;

use BitPayKeyUtils\Util\Base58;
use PHPUnit\Framework\TestCase;

class Base58Test extends TestCase
{
    public function testInstanceOf(): void
    {
        $base58 = $this->createClassObject();
        self::assertInstanceOf(Base58::class, $base58);
    }

    public function testEncodeException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid Length');

        $base58 = $this->createClassObject();
        $base58->encode('0x16p4t');
    }

    public function testEncode(): void
    {
        $base58 = $this->createClassObject();
        self::assertSame('P', $base58->encode('0x16'));
    }

    public function testEncode2(): void
    {
        $base58 = $this->createClassObject();
        self::assertSame('1', $base58->encode('00'));
    }

    public function testDecode(): void
    {
        $base58 = $this->createClassObject();
        self::assertSame('4f59cb', $base58->decode('Test'));
    }

    public function testDecode2(): void
    {
        $base58 = $this->createClassObject();
        self::assertSame('02bf547c6d249ea9', $base58->decode('Test 5 T 2'));
    }

    public function testDecode3(): void
    {
        $base58 = $this->createClassObject();
        self::assertSame('00', $base58->decode('1'));
    }

    private function createClassObject(): Base58
    {
        return new Base58();
    }
}
