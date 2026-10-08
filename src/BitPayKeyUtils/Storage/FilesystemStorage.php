<?php

namespace BitPayKeyUtils\Storage;

use BitPayKeyUtils\KeyHelper\KeyInterface;
use Exception;

/**
 * Used to persist keys to the filesystem
 */
class FilesystemStorage implements StorageInterface
{
    /**
     * @inheritdoc
     */
    public function persist(KeyInterface $key)
    {
        $path = $key->getId();
        $this->writeOwnerOnlyFile($path, serialize($key));
    }

    /**
     * @inheritdoc
     * @throws Exception
     * @throws Exception
     */
    public function load($id)
    {
        if (!is_file($id)) {
            throw new Exception(sprintf('Could not find "%s"', $id));
        }

        if (!is_readable($id)) {
            throw new Exception(sprintf('"%s" cannot be read, check permissions', $id));
        }

        return unserialize(file_get_contents($id));
    }

    /**
     * Writes the key file so only its owner can read and write it (mode 0600).
     *
     * The umask is set to 0077 while the file is created, so the key is never readable by other
     * users, not even for a moment. If the file already exists, its mode is changed to 0600 before
     * and after writing. On Windows, chmod only controls the read-only flag, so the file keeps the
     * default permissions there.
     *
     * @param string $path
     * @param string $data
     */
    private function writeOwnerOnlyFile(string $path, string $data): void
    {
        if (is_file($path)) {
            chmod($path, 0600);
        }

        $previousUmask = umask(0077);
        try {
            $written = file_put_contents($path, $data);
        } finally {
            umask($previousUmask);
        }

        if (false !== $written) {
            chmod($path, 0600);
        }
    }
}
