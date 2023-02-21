<?php

namespace Module\Services\FileStorage\Infrastructure\Repository;

use Module\Services\FileStorage\Application\Dto\FileInfoDto;
use Module\Services\FileStorage\Domain\Repository\StorageRepositoryInterface;
use Module\Services\FileStorage\Domain\Service\PathGeneratorInterface;

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

    public function get(string $guid, int $part = null): ?string
    {
        $filename = $this->pathGenerator->path($guid, $part);
        if (!file_exists($filename)) {
            return null;
        }

        return (string)file_get_contents($filename);
    }

    public function put(string $guid, string $contents): bool
    {
        $filename = $this->pathGenerator->path($guid);
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

    public function delete(string $guid): bool
    {
        return unlink($this->pathGenerator->path($guid));
    }

    public function fileInfo(string $guid, int $part = null): FileInfoDto
    {
        $filename = $this->pathGenerator->path($guid, $part);
        //$info->path = $filename;
        $exists = file_exists($filename);
        if ($exists) {
            return new FileInfoDto(
                true,
                filesize($filename),
                finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filename),
                filemtime($filename)
            );
        } else {
            return new FileInfoDto(false, 0, '', 0);
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

        try {
            mkdir($path, $mode, true);
        } catch (\Exception $e) {
            throw new \Exception('Cant create folder "' . $path . '"', 0, $e);
        }
    }
}
