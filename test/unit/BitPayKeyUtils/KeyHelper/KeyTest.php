<?php

declare(strict_types=1);

namespace BitPayKeyUtils\UnitTest\KeyHelper;

use BitPayKeyUtils\KeyHelper\Key;
use PHPUnit\Framework\TestCase;

class KeyTest extends TestCase
{
    public function testCreate(): void
    {
        $testedObject = $this->getTestedClass();
        $result = $testedObject::create();
        self::assertInstanceOf(Key::class, $result);
    }

    public function testGetIdWhenIdNotSet(): void
    {
        $testedObject = $this->getTestedClass();
        $result = $testedObject->getId();
        self::assertEmpty($result);
    }

    public function testGetIdWhenIdSet(): void
    {
        $id = 'test';
        $testedObject = $this->getTestedClass($id);
        $result = $testedObject->getId();
        self::assertSame($id, $result);
    }

    public function testGetHex(): void
    {
        $testedObject = $this->getTestedClass();
        $exampleValue = 'test';

        $this->setProtectedPropertyValue($testedObject, 'hex', $exampleValue);
        $result = $testedObject->getHex();
        self::assertSame($exampleValue, $result);
    }

    public function testGetDec(): void
    {
        $testedObject = $this->getTestedClass();
        $exampleValue = 'test';

        $this->setProtectedPropertyValue($testedObject, 'dec', $exampleValue);
        $result = $testedObject->getDec();
        self::assertSame($exampleValue, $result);
    }

    public function testSerialize(): void
    {
        $exampleId = 'test';

        $testedObject = $this->getTestedClass($exampleId);
        $result = $testedObject->serialize();
        self::assertIsString($result);
        self::assertStringContainsString($exampleId, $result);
    }

    public function testUnserialize(): void
    {
        $data = serialize(['id', 'x', 'y', 'hex', 'dec']);

        $testedObject = $this->getTestedClass();
        self::assertEmpty($testedObject->getId());

        $testedObject->unserialize($data);

        self::assertSame('id', $testedObject->getId());
        self::assertSame('x', $testedObject->getX());
        self::assertSame('y', $testedObject->getY());
        self::assertSame('hex', $testedObject->getHex());
        self::assertSame('dec', $testedObject->getDec());
    }

    public function testIsGenerated(): void
    {
        $testedObject = $this->getTestedClass();
        self::assertIsBool($testedObject->isGenerated());

        $this->setProtectedPropertyValue($testedObject, 'hex', 'test');
        self::assertTrue($testedObject->isGenerated());
    }

    private function setProtectedPropertyValue(&$instance, $propertyName, $propertyValue): void
    {
        $reflection = new \ReflectionProperty(get_class($instance), $propertyName);
        $reflection->setAccessible(true);
        $reflection->setValue($instance, $propertyValue);
    }

    private function getTestedClass($id = null): Key
    {
        return new class($id) extends Key {
            public function generate(): bool
            {
                return true;
            }

            public function isValid(): bool
            {
                return true;
            }
        };
    }
}