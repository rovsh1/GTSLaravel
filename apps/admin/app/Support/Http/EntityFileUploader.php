<?php

namespace App\Admin\Support\Http;

use App\Core\Contracts\File\FileInterface;
use App\Core\Support\Facades\FileAdapter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class EntityFileUploader
{
    private array $options = [];

    private mixed $data = null;

    public function __construct(private readonly Model $entity, private readonly string $fileType) {}

    public function multiple(): static
    {
        $this->options['multiple'] = true;
        return $this;
    }

    public function data(mixed $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function upload(mixed $data = null): bool
    {
        if (null !== $data) {
            $this->data = $data;
        } elseif (empty($this->data)) {
            return false;
        }

        if ($this->data instanceof UploadedFile) {
            return $this->uploadSingle($this->data);
        }

        return false;
    }

    private function uploadSingle(UploadedFile $uploadedFile): bool
    {
        $file = ($this->fileType)::findByEntity($this->entity->id);
        /** @var FileInterface $file */
        if ($file) {
            FileAdapter::put($file->guid(), $uploadedFile->get());
        } else {
            FileAdapter::create($this->fileType, $this->entity->id, $uploadedFile->getClientOriginalName(), $uploadedFile->get());
        }
        return true;
    }
}