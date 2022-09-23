<?php

use BitPayKeyUtils\KeyHelper\PrivateKey;
use BitPayKeyUtils\KeyHelper\PublicKey;
use BitPayKeyUtils\KeyHelper\SinKey;
use PHPUnit\Framework\TestCase;

class PublicKeyTest extends TestCase
{
    const EXAMPLE_HEX = 'FFF333';
    const EXAMPLE_DEC = '111';

    public function testCreateFromPrivateKey()
    {
        $privateKeyMock = $this->createMock(PrivateKey::class);

        $result = PublicKey::createFromPrivateKey($privateKeyMock);
        $this->assertInstanceOf(PublicKey::class, $result);
    }

    /**
     * @throws ReflectionException
     */
    public function testSetPrivateKey()
    {
        $privateKeyMock = $this->createMock(PrivateKey::class);

        $testedObject = $this->getTestedClass();
        $testedObject->setPrivateKey($privateKeyMock);
        $this->assertEquals($privateKeyMock, $this->accessProtected($testedObject, 'privateKey'));
    }

    public function test__toString_when_x_is_null()
    {
        $testedObject = $this->getTestedClass();
        $this->assertEquals('', (string)$testedObject);
    }

    /**
     * @throws ReflectionException
     */
    public function test__toString_when_x_is_not_null_and_math_mod_is_1()
    {
        $testedObject = $this->getTestedClass();

        $this->setProtectedPropertyValue($testedObject, 'x', 1);
        $this->setProtectedPropertyValue($testedObject, 'y', 1);

        $toStringCompressionResult = '031';
        $this->assertEquals($toStringCompressionResult, (string)$testedObject);
    }

    /**
     * @throws ReflectionException
     */
    public function test__toString_when_x_is_not_null_and_math_mod_is_not_1()
    {
        $testedObject = $this->getTestedClass();

        $this->setProtectedPropertyValue($testedObject, 'x', 1);
        $this->setProtectedPropertyValue($testedObject, 'y', 4);

        $toStringCompressionResult = '021';
        $this->assertEquals($toStringCompressionResult, (string)$testedObject);
    }

    public function testIsValidWhenPublicKeyIsBlank()
    {
        $testedObject = $this->getTestedClass();
        $result = $testedObject->isValid();

        $this->assertFalse($result);
    }

    /**
     * @throws ReflectionException
     */
    public function testIsValidWhenIsValid()
    {
        $testedObject = $this->getTestedClass();
        $this->setProtectedPropertyValue($testedObject, 'dec', self::EXAMPLE_DEC);
        $this->setProtectedPropertyValue($testedObject, 'hex', self::EXAMPLE_HEX);
        $result = $testedObject->isValid();

        $this->assertTrue($result);
    }

    /**
     * @throws ReflectionException
     */
    public function testIsValidWhenIsInvalid()
    {
        $testedObject = $this->getTestedClass();
        $this->setProtectedPropertyValue($testedObject, 'dec', 10);
        $this->setProtectedPropertyValue($testedObject, 'hex', 'FF5733');
        $result = $testedObject->isValid();

        $this->assertFalse($result);
    }

    /**
     * @throws ReflectionException
     */
    public function testGetSinWhenKeyNotGenerated()
    {
        $testedObject = $this->getTestedClass();
        $this->setProtectedPropertyValue($testedObject, 'hex', null);

        $this->expectException(Exception::class);
        $testedObject->getSin();
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function testGetSin()
    {
        $testedObject = $this->getTestedClass();
        $privateKeyMock = $this->getValidPrivateKeyMock();
        $testedObject->setPrivateKey($privateKeyMock);

        $result = $testedObject->getSin();
        $this->assertInstanceOf(SinKey::class, $result);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function testGenerate()
    {
        $testedObject = $this->getTestedClass();

        $privateKeyMock = $this->getValidPrivateKeyMock();
        $result = $testedObject->generate($privateKeyMock);

        $this->assertInstanceOf(PublicKey::class, $result);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function testGenerateWhenHexNotEmpty()
    {
        $testedObject = $this->getTestedClass();
        $privateKeyMock = $this->createMock(PrivateKey::class);
        $this->setProtectedPropertyValue($testedObject, 'hex', self::EXAMPLE_HEX);
        $result = $testedObject->generate($privateKeyMock);

        $this->assertInstanceOf(PublicKey::class, $result);
    }

    /**
     * @throws Exception
     */
    public function testGenerateWhenPrivateKeyInvalid()
    {
        $testedObject = $this->getTestedClass();
        $privateKeyMock = $this->createMock(PrivateKey::class);
        $privateKeyMock->expects($this->any())->method('isValid')->willReturn(false);

        $this->expectException(Exception::class);
        $result = $testedObject->generate($privateKeyMock);

        $this->assertInstanceOf(PublicKey::class, $result);
    }

    /**
     * @throws ReflectionException
     */
    private function accessProtected($obj, $prop)
    {
        $reflection = new ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
    }

    /**
     * @throws ReflectionException
     */
    private function setProtectedPropertyValue(&$instance, $propertyName, $propertyValue)
    {
        $reflection = new \ReflectionProperty(get_class($instance), $propertyName);
        $reflection->setAccessible(true);
        $reflection->setValue($instance, $propertyValue);
    }

    private function getTestedClass(): PublicKey
    {
        return new PublicKey();
    }

    /**
     * @throws ReflectionException
     */
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