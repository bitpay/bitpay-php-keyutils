<?php

declare(strict_types=1);

namespace BitPayKeyUtils\UnitTest\Util;

use BitPayKeyUtils\Util\SecureRandom;
use PHPUnit\Framework\TestCase;

class SecureRandomTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $secureRandom = $this->createClassObject();
        self::assertInstanceOf(SecureRandom::class, $secureRandom);
    }

    /**
     * @throws \ReflectionException
     */
    public function testHasOpenSSL(): void
    {
        $secureRandom = $this->createClassObject();
        $secureRandom::hasOpenSSL();

        $reflection = new \ReflectionProperty($secureRandom, 'hasOpenSSL');
        $reflection->setAccessible(true);

        self::assertTrue($reflection->getValue());
        self::assertTrue(property_exists($secureRandom, 'hasOpenSSL'));
    }

    public function testGenerateRandom(): void
    {
        $secureRandom = $this->createClassObject();
        $secureRandom::generateRandom();
        self::assertIsString($secureRandom::generateRandom());
    }

    public function testGenerateRandomException(): void
    {
        $this->expectException(\Exception::class);

        $reflection = new \ReflectionProperty(SecureRandom::class, 'hasOpenSSL');
        $reflection->setAccessible(true);
        $reflection->setValue(null, false);

        $secureRandom = $this->createClassObject();
        $secureRandom::generateRandom();
    }

    private function createClassObject()
    {
        return new SecureRandom();
    }
}
