<?php

declare(strict_types=1);

namespace BitPayKeyUtils\UnitTest\KeyHelper;

use BitPayKeyUtils\KeyHelper\PrivateKey;
use BitPayKeyUtils\KeyHelper\PublicKey;
use BitPayKeyUtils\KeyHelper\SinKey;
use PHPUnit\Framework\TestCase;

class PublicKeyTest extends TestCase
{
    private const EXAMPLE_HEX = 'FFF333';
    private const EXAMPLE_DEC = '111';

    public function testCreateFromPrivateKey(): void
    {
        $privateKeyMock = $this->createMock(PrivateKey::class);

        $result = PublicKey::createFromPrivateKey($privateKeyMock);
        self::assertInstanceOf(PublicKey::class, $result);
    }

    public function testSetPrivateKey(): void
    {
        $privateKeyMock = $this->createMock(PrivateKey::class);

        $testedObject = $this->getTestedClassObject();
        $testedObject->setPrivateKey($privateKeyMock);
        self::assertSame($privateKeyMock, $this->accessProtected($testedObject, 'privateKey'));
    }

    public function testToStringWhenXIsNull(): void
    {
        $testedObject = $this->getTestedClassObject();
        self::assertSame('', (string)$testedObject);
    }

    public function testToStringWhenXIsNotNullAndMathModIs1(): void
    {
        $testedObject = $this->getTestedClassObject();

        $this->setProtectedPropertyValue($testedObject, 'x', 1);
        $this->setProtectedPropertyValue($testedObject, 'y', 1);

        $toStringCompressionResult = '031';
        self::assertSame($toStringCompressionResult, (string)$testedObject);
    }

    public function testToStringWhenXIsNotNullAndMathModIsNot1(): void
    {
        $testedObject = $this->getTestedClassObject();

        $this->setProtectedPropertyValue($testedObject, 'x', 1);
        $this->setProtectedPropertyValue($testedObject, 'y', 4);

        $toStringCompressionResult = '021';
        self::assertSame($toStringCompressionResult, (string)$testedObject);
    }

    public function testIsValidWhenPublicKeyIsBlank(): void
    {
        $testedObject = $this->getTestedClassObject();
        $result = $testedObject->isValid();

        self::assertFalse($result);
    }

    public function testIsValidWhenIsValid(): void
    {
        $testedObject = $this->getTestedClassObject();
        $this->setProtectedPropertyValue($testedObject, 'dec', self::EXAMPLE_DEC);
        $this->setProtectedPropertyValue($testedObject, 'hex', self::EXAMPLE_HEX);
        $result = $testedObject->isValid();

        self::assertTrue($result);
    }

    public function testIsValidWhenIsInvalid(): void
    {
        $testedObject = $this->getTestedClassObject();
        $this->setProtectedPropertyValue($testedObject, 'dec', null);
        $this->setProtectedPropertyValue($testedObject, 'hex', 'FF5733');
        $result = $testedObject->isValid();

        self::assertFalse($result);
    }

    public function testGetSinWhenKeyNotGenerated(): void
    {
        $testedObject = $this->getTestedClassObject();
        $this->setProtectedPropertyValue($testedObject, 'hex', null);

        $this->expectException(\Exception::class);
        $testedObject->getSin();
    }

    public function testGetSin(): void
    {
        $testedObject = $this->getTestedClassObject();
        $privateKeyMock = $this->getValidPrivateKeyMock();
        $testedObject->setPrivateKey($privateKeyMock);

        $result = $testedObject->getSin();
        self::assertInstanceOf(SinKey::class, $result);
    }

    public function testGenerate(): void
    {
        $testedObject = $this->getTestedClassObject();

        $privateKeyMock = $this->getValidPrivateKeyMock();
        $result = $testedObject->generate($privateKeyMock);

        self::assertInstanceOf(PublicKey::class, $result);
    }

    public function testGenerateWhenHexNotEmpty(): void
    {
        $testedObject = $this->getTestedClassObject();
        $privateKeyMock = $this->createMock(PrivateKey::class);
        $this->setProtectedPropertyValue($testedObject, 'hex', self::EXAMPLE_HEX);
        $result = $testedObject->generate($privateKeyMock);

        self::assertInstanceOf(PublicKey::class, $result);
    }

    public function testGenerateWhenPrivateKeyInvalid(): void
    {
        $testedObject = $this->getTestedClassObject();
        $privateKeyMock = $this->createMock(PrivateKey::class);
        $privateKeyMock->expects($this->any())->method('isValid')->willReturn(false);

        $this->expectException(\Exception::class);
        $result = $testedObject->generate($privateKeyMock);

        self::assertInstanceOf(PublicKey::class, $result);
    }

    private function accessProtected($obj, $prop)
    {
        $reflection = new \ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
    }

    private function setProtectedPropertyValue(&$instance, $propertyName, $propertyValue): void
    {
        $reflection = new \ReflectionProperty(get_class($instance), $propertyName);
        $reflection->setAccessible(true);
        $reflection->setValue($instance, $propertyValue);
    }

    private function getTestedClassObject(): PublicKey
    {
        return new PublicKey();
    }

    private function getValidPrivateKeyMock()
    {
        $privateKeyMock = $this->createMock(PrivateKey::class);
        $privateKeyMock->expects($this->any())->method('getHex')->willReturn(self::EXAMPLE_HEX);
        $this->setProtectedPropertyValue($privateKeyMock, 'dec', self::EXAMPLE_DEC);
        $this->setProtectedPropertyValue($privateKeyMock, 'hex', self::EXAMPLE_HEX);
        $privateKeyMock->expects($this->any())->method('isValid')->willReturn(true);

        return $privateKeyMock;
    }
}
