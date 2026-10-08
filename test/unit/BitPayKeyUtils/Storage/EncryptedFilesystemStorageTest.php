<?php

declare(strict_types=1);

namespace BitPayKeyUtils\UnitTest\Storage;

use BitPayKeyUtils\KeyHelper\Key;
use BitPayKeyUtils\KeyHelper\KeyInterface;
use BitPayKeyUtils\Storage\EncryptedFilesystemStorage;
use PHPUnit\Framework\MockObject\MockObject;
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
        /**
         * @var KeyInterface|MockObject
         */
        $keyInterface = $this->getMockBuilder(Key::class)->setMockClassName('KeyMock2')->getMock();
        $keyInterface->method('getId')->willReturn(__DIR__ . '/test11.txt');
        self::assertFileExists(__DIR__ . '/test11.txt');
        $encryptedFilesystemStorage->persist($keyInterface);
        self::assertFileExists(__DIR__ . '/test11.txt');
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

    public function testPersistCreatesFileReadableOnlyByOwner(): void
    {
        $this->skipOnWindows();
        $path = $this->tempPath();

        $previousUmask = umask(0022);
        try {
            $this->createClassObject()->persist($this->keyWithId($path, 'KeyMockEnc1'));
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

        $this->createClassObject()->persist($this->keyWithId($path, 'KeyMockEnc2'));

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

    private function createClassObject(): EncryptedFilesystemStorage
    {
        return new EncryptedFilesystemStorage('test');
    }
}
