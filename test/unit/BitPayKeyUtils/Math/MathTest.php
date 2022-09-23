<?php

use BitPayKeyUtils\Math\Math;
use PHPUnit\Framework\TestCase;

class MathTest extends TestCase
{
    public function testInstanceOf()
    {
        $math = $this->createClassObject();
        $this->assertInstanceOf(Math::class, $math);
    }

    public function testGetEngine()
    {
        $expectedEngine = 'Test engine';

        $math = $this->createClassObject();
        $math::setEngine($expectedEngine);
        $this->assertEquals($expectedEngine, $math::getEngine());
    }

    public function testGetEngineName()
    {
        $expectedEngineName = 'Test engine name';

        $math = $this->createClassObject();
        $math::setEngineName($expectedEngineName);
        $this->assertEquals($expectedEngineName, $math::getEngineName());
    }

    private function createClassObject()
    {
        return new Math();
    }
}
