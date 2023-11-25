<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Application\Admin\UseCase\Document;

use Illuminate\Support\Facades\DB;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Dto\UploadedFileDto;

class UploadDocumentFiles implements UseCaseInterface
{
    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {}

    public function execute(int $documentId, array $files): void
    {
        $existFilesQuery = DB::table('client_document_files')->where('document_id', $documentId);
        $guids = $existFilesQuery->get()->pluck('guid')->toArray();
        $existFilesQuery->delete();
        foreach ($guids as $guid) {
            $this->fileStorageAdapter->delete($guid);
        }

        $values = [];
        /** @var UploadedFileDto $uploadedFileDto */
        foreach ($files as $uploadedFileDto) {
            $fileDto = $this->fileStorageAdapter->create($uploadedFileDto->name, $uploadedFileDto->contents);
            $values[] = ['document_id' => $documentId, 'guid' => $fileDto->guid];
        }

        if ($values) {
            DB::table('client_document_files')->insert($values);
        }
    }
}
