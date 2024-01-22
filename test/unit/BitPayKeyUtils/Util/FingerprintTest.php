<?php

declare(strict_types=1);

namespace BitPayKeyUtils\UnitTest\Util;

use BitPayKeyUtils\Util\Fingerprint;
use PHPUnit\Framework\TestCase;

class FingerprintTest extends TestCase
{
    public function testGenerate(): void
    {
        $fingerprint = new Fingerprint();
        self::assertIsString($fingerprint::generate());
    }

    public function testGenerateIssetFinHash(): void
    {
        $expectedValue = 'ce9c26116feb916c356b5313226ff177bf30f819';

        $reflection = new \ReflectionProperty(Fingerprint::class, 'finHash');
        $reflection->setAccessible(true);
        $reflection->setValue(null, $expectedValue);

        $fingerprint = new Fingerprint();
        $actualValue = $fingerprint::generate();

        self::assertIsString($actualValue);
        self::assertSame($expectedValue, $actualValue);
    }
}
