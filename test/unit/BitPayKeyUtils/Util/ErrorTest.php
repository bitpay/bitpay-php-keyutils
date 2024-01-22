<?php

declare(strict_types=1);

namespace BitPayKeyUtils\UnitTest\Util;

use PHPUnit\Framework\TestCase;
use BitPayKeyUtils\Util\Error;

class ErrorTest extends TestCase
{
    public function testBacktraceWhenPrintIsFalse(): void
    {
        $testedObject = $this->getTestedClassObject();
        $result = $testedObject->backtrace();
        self::assertSame('backtrace', $result[0]['function']);
    }

    public function testBacktraceWhenPrintIsTrue(): void
    {
        $testedObject = $this->getTestedClassObject();
        ob_start();
        $testedObject->backtrace(true);
        $result = ob_get_clean();
        self::assertIsString($result);
    }

    public function testLastWhenNoErrorOccured(): void
    {
        error_clear_last();
        $testedObject = $this->getTestedClassObject();
        $result = $testedObject->last();
        self::assertNull($result);
    }

    public function testLog(): void
    {
        $exampleLogMessage = 'test';
        $testedObject = $this->getTestedClassObject();
        $errorLogTemporaryFile = tmpfile();
        $errorLogLocationBackup = ini_set('error_log', stream_get_meta_data($errorLogTemporaryFile)['uri']);
        $testedObject->log($exampleLogMessage);
        ini_set('error_log', $errorLogLocationBackup);
        $result = stream_get_contents($errorLogTemporaryFile);

        self::assertStringContainsString($exampleLogMessage, $result);
    }

    public function testReportingWithNoParam(): void
    {
        $testedObject = $this->getTestedClassObject();
        $result = $testedObject->reporting();
        self::assertSame(error_reporting(), $result);
    }

    public function testReportingWithLevel(): void
    {
        $testedObject = $this->getTestedClassObject();
        $exampleReportingLevel = 32767;
        $testedObject->reporting($exampleReportingLevel);
        $currentLevel = error_reporting();
        self::assertSame($exampleReportingLevel, $currentLevel);
    }

    public function testHandlerWithNoParams(): void
    {
        $testedObject = $this->getTestedClassObject();
        $result = $testedObject->handler();
        self::assertTrue($result);
    }

    public function testHandlerWithActionSet(): void
    {
        $testedObject = $this->getTestedClassObject();
        $result = $testedObject->handler('error', 'set', null);
        self::assertInstanceOf(\PHPUnit\Runner\ErrorHandler::class, $result);
    }

    public function testHandlerWithActionFalse(): void
    {
        $testedObject = $this->getTestedClassObject();
        $result = $testedObject->handler('error', false);
        self::assertFalse($result);
    }

    public function testHandlerWithTypeExceptionAndNoAction(): void
    {
        $testedObject = $this->getTestedClassObject();
        $result = $testedObject->handler('exception', false);
        self::assertFalse($result);
    }

    public function testHandlerWithTypeExceptionAndActionSet(): void
    {
        $testedObject = $this->getTestedClassObject();
        $result = $testedObject->handler('exception', 'set', null);
        self::assertNull($result);
    }

    public function testHandlerWithTypeExceptionAndActionRestore(): void
    {
        $testedObject = $this->getTestedClassObject();
        $result = $testedObject->handler('exception', 'restore', null);
        self::assertTrue($result);
    }

    public function testHandlerWithUnhandledType(): void
    {
        $testedObject = $this->getTestedClassObject();
        $result = $testedObject->handler('asd', 'restore', null);
        self::assertFalse($result);
    }

    public function testRaise(): void
    {
        set_error_handler(
            static function ( $errno, $errstr ) {
                restore_error_handler();
                throw new \Exception( $errstr, $errno );
            },
            E_ALL
        );
        $testedObject = $this->getTestedClassObject();
        $this->expectExceptionMessage('error');

        $result = $testedObject->raise('error');
        self::assertTrue($result);
    }

    private function getTestedClassObject(): Error
    {
        return new Error();
    }
}
