<?php

namespace App\Core\Support\Adapters;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use App\Core\Contracts\File\FileInterface;
use Module\Services\FileStorage\Application\Dto\FileDto;
use Module\Services\FileStorage\Application\Dto\FileInfoDto;

class FileAdapter extends AbstractModuleAdapter
{
    public function find(string $guid): ?FileInterface
    {
        $fileDto = $this->request('find', ['guid' => $guid]);

        return $this->fileFactory($fileDto);
    }

    public function getEntityFile(int $entityId, string $fileType): ?FileInterface
    {
        $fileDto = $this->request('getEntityFile', [
            'entityId' => $entityId,
            'fileType' => $fileType
        ]);

        return $this->fileFactory($fileDto);
    }

    public function getEntityFiles(int $entityId, string $fileType): Collection
    {
        return collect(
            $this->request('getEntityFiles', [
                'entityId' => $entityId,
                'fileType' => $fileType
            ])
        )->map(fn($fileDto) => $this->fileFactory($fileDto));
    }

    public function getContents(string|FileInterface $guid, ?int $part): ?string
    {
        return $this->request('getContents', [
            'guid' => is_string($guid) ? $guid : $guid->guid(),
            'part' => $part
        ]);
    }

    public function fileInfo(string|FileInterface $guid, ?int $part): ?FileInfoDto
    {
        return $this->request('fileInfo', [
            'guid' => is_string($guid) ? $guid : $guid->guid(),
            'part' => $part
        ]);
    }

    public function url(string|FileInterface $guid, ?int $part): ?string
    {
        return $this->request('url', [
            'guid' => is_string($guid) ? $guid : $guid->guid(),
            'part' => $part
        ]);
    }

    public function create(
        string $fileType,
        ?int $entityId,
        string $name = null,
        string $contents = null
    ): ?FileInterface {
        $fileDto = $this->request('create', [
            'fileType' => $fileType,
            'entityId' => $entityId,
            'name' => $name,
            'contents' => $contents,
        ]);

        return $this->fileFactory($fileDto);
    }

    public function put(string|FileInterface $guid, string $contents): bool
    {
        return $this->request('put', [
            'guid' => is_string($guid) ? $guid : $guid->guid(),
            'contents' => $contents,
        ]);
    }

    public function delete(string|FileInterface $guid): bool
    {
        return $this->request('delete', [
            'guid' => is_string($guid) ? $guid : $guid->guid(),
        ]);
    }

    public function uploadOrCreate(
        FileInterface|null $file,
        UploadedFile $uploadedFile,
        string $fileType,
        ?int $entityId
    ): ?FileInterface {
        if (is_null($file)) {
            return $this->create($fileType, $entityId, $uploadedFile->getClientOriginalName(), $uploadedFile->get());
        } else {
            $this->put($file, $uploadedFile->get());

            return $file;
        }
    }

    private function fileFactory(?FileDto $fileDto): ?FileInterface
    {
        return $fileDto
            ? new $fileDto->type(
                $fileDto->guid,
                $fileDto->entityId,
                $fileDto->name,
                $fileDto->url,
            )
            : null;
    }

    protected function getModuleKey(): string
    {
        return 'files';
    }
}