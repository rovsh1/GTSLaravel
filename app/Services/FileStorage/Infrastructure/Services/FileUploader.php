<?php

namespace GTS\Services\FileStorage\Infrastructure\Services;

use Illuminate\Http\UploadedFile;

use GTS\Services\FileStorage\Infrastructure\Facade\WriterFacadeInterface;

class FileUploader
{
    private string $guid;

    public function __construct(
        private readonly WriterFacadeInterface $fileWriter
    ) {}

    public function with(string $guid): static
    {
        $this->guid = $guid;
        return $this;
    }

    public function create(string $fileType, ?int $entityId): static
    {
        $file = $this->fileWriter->create($fileType, $entityId);

        return $this->with($file->guid);
    }

    public function upload(UploadedFile $uploadedFile): static
    {
        if (!$this->guid)
            throw new \Exception('Guid undefined');

        if (!$uploadedFile->isValid())
            throw new \Exception('Uploaded file invalid');

        $this->fileWriter->put($this->guid, $uploadedFile->get());

        //$this->fileWriter->update(['name' => $uploadedFile->getClientOriginalName()]);

        return $this;
    }
}
