<?php

use BitPayKeyUtils\Storage\FilesystemStorage;
use PHPUnit\Framework\TestCase;

class FilesystemStorageTest extends TestCase
{
    public function testInstanceOf()
    {
        $filesystemStorage = $this->createClassObject();

        $this->assertInstanceOf(FilesystemStorage::class, $filesystemStorage);
    }

    private function createClassObject()
    {
        return new FilesystemStorage();
    }
}
