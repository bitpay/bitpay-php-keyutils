<?php

declare(strict_types=1);

namespace BitPayKeyUtils\UnitTest\Storage;

use BitPayKeyUtils\KeyHelper\Key;
use BitPayKeyUtils\Storage\FilesystemStorage;
use PHPUnit\Framework\MockObject\MockObject;
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
        /**
         * @var Key|MockObject
         */
        $keyInterface = $this->getMockBuilder(Key::class)->setMockClassName('KeyMock1')->getMock();
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

    public function testPersistCreatesFileReadableOnlyByOwner(): void
    {
        $this->skipOnWindows();
        $path = $this->tempPath();

        $previousUmask = umask(0022);
        try {
            $this->createClassObject()->persist($this->keyWithId($path, 'KeyMockFs1'));
            self::assertSame(0022, umask(), 'persist() must restore the umask');
        } finally {
            umask($previousUmask);
        }

        clearstatcache(true, $path); // PHP caches stat() results, including the file mode
        self::assertSame(0600, fileperms($path) & 0777);
        unlink($path);
    }

    public function testPersistTightensExistingFile(): void
    {
        $this->skipOnWindows();
        $path = $this->tempPath();
        file_put_contents($path, 'old key');
        chmod($path, 0644);

        $this->createClassObject()->persist($this->keyWithId($path, 'KeyMockFs2'));

        clearstatcache(true, $path); // PHP caches stat() results, including the file mode
        self::assertSame(0600, fileperms($path) & 0777);
        unlink($path);
    }

    private function keyWithId(string $path, string $mockName): Key
    {
        $key = $this->getMockBuilder(Key::class)->setMockClassName($mockName)->getMock();
        $key->method('getId')->willReturn($path);

        return $key;
    }

    private function tempPath(): string
    {
        $path = tempnam(sys_get_temp_dir(), 'bitpay-key-');
        unlink($path);

        return $path;
    }

    private function skipOnWindows(): void
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            self::markTestSkipped('POSIX permissions do not apply on Windows');
        }
    }

    private function createClassObject(): FilesystemStorage
    {
        return new FilesystemStorage();
    }
}
