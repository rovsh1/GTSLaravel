<?php

namespace Module\Shared\Support\Adapter;

use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Shared\Dto\FileDto;
use Module\Support\FileStorage\Application\Dto\CreateFileRequestDto;
use Module\Support\FileStorage\Application\Dto\FileInfoDto;
use Module\Support\FileStorage\Application\Dto\UpdateFileRequestDto;
use Module\Support\FileStorage\Application\UseCase\CreateFile;
use Module\Support\FileStorage\Application\UseCase\DeleteFile;
use Module\Support\FileStorage\Application\UseCase\FindFile;
use Module\Support\FileStorage\Application\UseCase\GetFileInfo;
use Module\Support\FileStorage\Application\UseCase\UpdateFile;

class FileStorageAdapter implements FileStorageAdapterInterface
{
    public function find(string $guid): ?FileDto
    {
        return app(FindFile::class)->execute($guid);
    }

    public function create(string $name, string $contents): FileDto
    {
        return app(CreateFile::class)->execute(new CreateFileRequestDto($name, $contents));
    }

    public function update(string $guid, string $name, string $contents): void
    {
        app(UpdateFile::class)->execute(new UpdateFileRequestDto($guid, $name, $contents));
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
        return app(GetFileInfo::class)->execute($guid);
    }

    public function delete(string $guid): void
    {
        app(DeleteFile::class)->execute($guid);
    }
}