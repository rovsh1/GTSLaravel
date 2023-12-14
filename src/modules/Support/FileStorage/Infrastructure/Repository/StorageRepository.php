<?php

namespace Module\Support\FileStorage\Infrastructure\Repository;

use Exception;
use Module\Support\FileStorage\Domain\Entity\File;
use Module\Support\FileStorage\Domain\Repository\StorageRepositoryInterface;
use Module\Support\FileStorage\Domain\Service\PathGeneratorInterface;

class StorageRepository implements StorageRepositoryInterface
{
    private readonly array $config;

    public function __construct(
        array $config,
        private readonly PathGeneratorInterface $pathGenerator
    ) {
        $this->config = [
            'dirMode' => $config['dir_mode'] ?? 0770,
            'fileMode' => $config['file_mode'] ?? 0660,
            'user' => $config['user'] ?? null,
            'group' => $config['group'] ?? null,
        ];
    }

    public function get(File $file, int $part = null): ?string
    {
        $filename = $this->pathGenerator->path($file, $part);
        if (!file_exists($filename)) {
            return null;
        }

        return (string)file_get_contents($filename);
    }

    /**
     * @throws Exception
     */
    public function put(File $file, string $contents): bool
    {
        $filename = $this->pathGenerator->path($file);
        $createdFlag = !file_exists($filename);

        if ($createdFlag) {
            $umask = umask(0);
            static::checkFileDirectory(dirname($filename), $this->config['dirMode']);
        }

        $lock = false;
        if (false === file_put_contents($filename, $contents, $lock ? LOCK_EX : 0)) {
            return false;
        }

        if ($createdFlag) {
            $this->chmod($filename, $umask);
        }

        return true;
    }

    public function delete(File $file): void
    {
        $filename = $this->pathGenerator->path($file);
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    private function chmod($filename, $umask): void
    {
        chmod($filename, $this->config['fileMode']);
        umask($umask);

        if ($this->config['group']) {
            chgrp($filename, $this->config['group']);
        }

        if ($this->config['user']) {
            chown($filename, $this->config['user']);
        }
    }

    private static function checkFileDirectory($path, $mode): void
    {
        if (is_dir($path)) {
            return;
        }

        if (!mkdir($path, $mode, true)) {
            throw new Exception('Cant create folder "' . $path . '"');
        }
    }
}
