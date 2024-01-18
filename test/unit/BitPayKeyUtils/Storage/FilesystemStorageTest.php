<?php

declare(strict_types=1);

namespace BitPayKeyUtils\UnitTest\Storage;

use BitPayKeyUtils\KeyHelper\Key;
use BitPayKeyUtils\Storage\FilesystemStorage;
use PHPUnit\Framework\TestCase;

class FilesystemStorageTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $filesystemStorage = $this->createClassObject();

        self::assertInstanceOf(FilesystemStorage::class, $filesystemStorage);
    }

    public function testPersist(): void
    {
        $filesystemStorage = $this->createClassObject();
        $keyInterface = $this->getMockBuilder(Key::class)->setMockClassName('KeyMock')->getMock();
        $keyInterface->method('getId')->willReturn(__DIR__ . '/test1.txt');
        self::assertFileExists(__DIR__ . '/test1.txt');
        @chmod(__DIR__ . 'test1.txt', 0777);
        $filesystemStorage->persist($keyInterface);
    }

    public function testLoadNotFindException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Could not find "'.__DIR__.'/test2.txt"');

        $filesystemStorage = $this->createClassObject();
        $filesystemStorage->load(__DIR__ . '/test2.txt');
    }

    // This test needs the user(not root) and the corresponding permissions (file cannot be read)
    /**
    public function testLoadNotPermissionException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('"' . __DIR__ . '/test3.txt" cannot be read, check permissions');

        $filesystemStorage = $this->createClassObject();
        $filesystemStorage->load(__DIR__ . '/test3.txt');
    }
    **/

    public function testLoad(): void
    {
        $expectedArray = ['Red', 'Green', 'Blue'];

        $filesystemStorage = $this->createClassObject();
        self::assertSame($expectedArray, $filesystemStorage->load(__DIR__ . '/test4.txt'));
    }

    private function createClassObject(): FilesystemStorage
    {
        return new FilesystemStorage();
    }
}
