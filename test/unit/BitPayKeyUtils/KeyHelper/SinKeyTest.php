<?php

declare(strict_types=1);

namespace BitPayKeyUtils\UnitTest\KeyHelper;

use BitPayKeyUtils\KeyHelper\PrivateKey;
use BitPayKeyUtils\KeyHelper\PublicKey;
use BitPayKeyUtils\KeyHelper\SinKey;
use PHPUnit\Framework\TestCase;

class SinKeyTest extends TestCase
{
    private ?SinKey $sinKey;

    public function setUp(): void
    {
        $this->sinKey = new SinKey();
    }

    public function test__toStringEmpty(): void
    {
        self::assertEmpty($this->sinKey->__toString());
    }

    public function test__toStringValue(): void
    {
        $publicKey = new PublicKey();
        $privateKey = new PrivateKey();
        $publicKey->generate($privateKey);
        $this->sinKey->setPublicKey($publicKey);
        $this->sinKey->generate();
        $property = $this->getAccessibleProperty(SinKey::class, 'value');
        $value = $property->getValue($this->sinKey);

        self::assertSame($value, $this->sinKey->__toString());
    }

    public function testSetPublicKey(): void
    {
        $publicKey = $this->getMockBuilder(PublicKey::class)->getMock();

        self::assertSame($this->sinKey, $this->sinKey->setPublicKey($publicKey));
    }

    public function testGenerateWithoutPublicKey(): void
    {
        $this->expectException(\Exception::class);
        $this->sinKey->generate();
    }

    public function testPublicKeyGenerateException(): void
    {
        $property = $this->getAccessibleProperty(SinKey::class, 'publicKey');
        $property->setValue($this->sinKey, '');
        $this->expectException(\Exception::class);
        $this->sinKey->generate();
    }

    public function testIsValid(): void
    {
        $publicKey = new PublicKey();
        $privateKey = new PrivateKey();
        $publicKey->generate($privateKey);
        $this->sinKey->setPublicKey($publicKey);
        $this->sinKey->generate();

        self::assertSame(true, $this->sinKey->isValid());
    }

    public function testIsValidFalse(): void
    {
        self::assertSame(false, $this->sinKey->isValid());
    }

    private function getAccessibleProperty(string $class, string $property): \ReflectionProperty
    {
        $reflection = new \ReflectionClass($class);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);

        return $property;
    }
}