<?php

namespace Module\Support\FileStorage\Console\Service;

use Module\Support\FileStorage\Domain\Service\PathGeneratorInterface;

class StorageCleaner
{
    private readonly string $basePath;

    private readonly string $pathPattern;

    public function __construct(PathGeneratorInterface $pathGenerator)
    {
        $this->basePath = $pathGenerator->basePath();
        $this->pathPattern = '/\/[a-z0-9]{2}\/[a-z0-9]{2}\/([a-z0-9]{32})\/([^\/]+)$/';
    }

    public function clean(): void
    {
        $this->clearBrokenFiles($this->basePath);
    }

    private function clearBrokenFiles(string $path): void
    {
        $handle = opendir($path);
        if (!$handle) {
            throw new \Exception('Cant open directory');
        }

        while (false !== ($entry = readdir($handle))) {
            if (in_array($entry, ['..', '.'])) {
                continue;
            }

            $filename = $path . DIRECTORY_SEPARATOR . $entry;
            if (is_file($filename)) {
                $this->checkFile($filename);
            } elseif (is_dir($filename)) {
                $this->clearBrokenFiles($filename);

                if ($this->isDirEmpty($filename)) {
                    rmdir($filename);
                }
            }
        }

        closedir($handle);
    }

    private function isDirEmpty(string $path): bool
    {
        return empty(glob($path . DIRECTORY_SEPARATOR . '*'));
    }

    private function checkFile(string $filename): void
    {
        $path = str_replace($this->basePath, '', $filename);
        if (!preg_match($this->pathPattern, $path)) {
            unlink($filename);
        }
    }
}
