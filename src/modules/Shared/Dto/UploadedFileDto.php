<?php

namespace Module\Shared\Dto;

use Illuminate\Http\UploadedFile;

final class UploadedFileDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $contents,
    ) {
    }

    public static function fromUploadedFile(UploadedFile $uploadedFile): UploadedFileDto
    {
        return new UploadedFileDto(
            $uploadedFile->getClientOriginalName(),
            $uploadedFile->get()
        );
    }

    /**
     * @param array $data
     * @return UploadedFileDto
     * @deprecated
     */
    public static function fromTmpData(array $data): UploadedFileDto
    {
        return new UploadedFileDto(
            $data['name'],
            file_get_contents(storage_path('tmp/' . $data['tmp_name']))
        );
    }
}
