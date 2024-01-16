<?php

namespace Shared\Support\Adapter;

use Gsdk\FileStorage\FileInfo;
use Gsdk\FileStorage\FileStorage;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Dto\FileDto;
use Sdk\Shared\Dto\FileInfoDto;

class FileStorageAdapter implements FileStorageAdapterInterface
{
    public function find(string $guid): ?FileDto
    {
        return ($fileInfo = FileStorage::find($guid))
            ? $this->makeDto($fileInfo)
            : null;
    }

    public function create(string $name, string $contents): FileDto
    {
        return $this->makeDto(FileStorage::create($name, $contents));
    }

    public function update(string $guid, string $name, string $contents): void
    {
        FileStorage::update($guid, $name, $contents);
    }

    public function updateOrCreate(?string $guid, string $name, string $contents): ?FileDto
    {
        if ($guid) {
            $this->update($guid, $name, $contents);

            return null;
        } else {
            return $this->create($name, $contents);
        }
    }

    public function getInfo(string $guid): ?FileInfoDto
    {
        $fileInfo = FileStorage::find($guid);
        if (!$fileInfo) {
            return null;
        }

        return new FileInfoDto(
            guid: $fileInfo->getGuid(),
            name: $fileInfo->getBasename(),
            url: $fileInfo->getUrl(),
            filename: $fileInfo->getPathname(),
            size: $fileInfo->getSize(),
            mimeType: $fileInfo->getMimeType(),
            lastModified: $fileInfo->getMTime(),
        );
    }

    public function delete(string $guid): void
    {
        FileStorage::delete($guid);
    }

    private function makeDto(FileInfo $fileInfo): FileDto
    {
        return new FileDto(
            guid: $fileInfo->getGuid(),
            name: $fileInfo->getBasename(),
            url: $fileInfo->getUrl()
        );
    }
}
