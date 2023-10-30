<?php

namespace Module\Shared\Support\UseCase;

use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Shared\Dto\UploadedFileDto;

/**
 * @deprecated
 */
abstract class AbstractUpdateModelFile
{
    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {
    }

    public function execute(int $modelId, UploadedFileDto $uploadedFile): void
    {
        $modeClass = $this->model();
        $model = $modeClass::find($modelId);
        if (!$model) {
            throw new \Exception($modeClass . ' not found', 404);
        }

        $fileField = $this->fileField();
        $fileDto = $this->fileStorageAdapter->updateOrCreate(
            $model->$fileField,
            $uploadedFile->name,
            $uploadedFile->contents
        );
        if ($fileDto) {
            $model->update([$fileField => $fileDto->guid]);
        }
    }

    abstract protected function model(): string;

    abstract protected function fileField(): string;
}
