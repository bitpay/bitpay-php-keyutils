<?php

declare(strict_types=1);

namespace BitPayKeyUtils\UnitTest\Storage;

use BitPayKeyUtils\KeyHelper\Key;
use BitPayKeyUtils\Storage\EncryptedFilesystemStorage;
use PHPUnit\Framework\TestCase;

class EncryptedFilesystemStorageTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $encryptedFilesystemStorage = $this->createClassObject();
        self::assertInstanceOf(EncryptedFilesystemStorage::class, $encryptedFilesystemStorage);
    }

    public function testPersist(): void
    {
        $encryptedFilesystemStorage = $this->createClassObject();
        $keyInterface = $this->getMockBuilder(Key::class)->setMockClassName('KeyMock')->getMock();
        $keyInterface->method('getId')->willReturn(__DIR__ . '/test11.txt');
        self::assertFileExists(__DIR__ . '/test11.txt');
        self::assertSame(null, $encryptedFilesystemStorage->persist($keyInterface));
    }

    public function testLoadNotFindException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Could not find "'.__DIR__.'/test2.txt"');

        $encryptedFilesystemStorage = $this->createClassObject();
        $encryptedFilesystemStorage->load(__DIR__ . '/test2.txt');
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
       $encryptedFilesystemStorage = $this->createClassObject();
       self::assertIsObject($encryptedFilesystemStorage->load(__DIR__ . '/test11.txt'));
    }

    private function createClassObject(): EncryptedFilesystemStorage
    {
        return new EncryptedFilesystemStorage('test');
    }
}
